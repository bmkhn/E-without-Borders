<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Member;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = Club::all();
        $positions = Position::all();

        if ($clubs->isEmpty()) {
            $this->command->warn('No clubs found. Skipping Member seeder.');
            return;
        }

        // Seed positions if none exist
        if ($positions->isEmpty()) {
            $positionNames = ['President', 'Vice President', 'Secretary', 'Treasurer', 'Auditor'];

            foreach ($positionNames as $name) {
                Position::firstOrCreate(['name' => $name]);
            }

            $positions = Position::all();
            $this->command->info('Created positions: ' . $positions->pluck('name')->implode(', '));
        }

        $membersData = [
            ['name' => 'Alice Johnson', 'contact' => '09171234567'],
            ['name' => 'Bob Smith', 'contact' => '09171234568'],
            ['name' => 'Carol Williams', 'contact' => '09171234569'],
        ];

        foreach ($clubs as $club) {
            foreach ($membersData as $index => $data) {
                $position = $positions->get($index % $positions->count());

                // Check if member already exists for this club
                $existing = Member::where('club_id', $club->id)
                    ->where('name', $data['name'])
                    ->first();

                if ($existing) {
                    continue;
                }

                // Create with slug included
                $member = new Member([
                    'club_id' => $club->id,
                    'position_id' => $position->id,
                    'name' => $data['name'],
                    'contact_number' => $data['contact'],
                ]);

                $member->applySlugFromName();
                $member->save();

                // Generate QR code for the member
                $this->generateQrCode($member);
            }

            $this->command->info("Created members for club: {$club->name}");
        }

        // Backfill QR codes for any existing members that don't have one
        $membersWithoutQr = Member::whereNull('qr_code')->get();

        if ($membersWithoutQr->isNotEmpty()) {
            $this->command->info('Generating QR codes for ' . $membersWithoutQr->count() . ' existing members without QR codes...');

            foreach ($membersWithoutQr as $member) {
                $this->generateQrCode($member);
            }

            $this->command->info('QR codes generated for existing members.');
        }
    }

    public function generateQrCode(Member $member): void
    {
        try {
            $profileUrl = route('member.profile', $member->slug);

            $qrSvg = app('qrcode')
                ->size(300)
                ->margin(5)
                ->color(245, 158, 11)
                ->backgroundColor(0, 0, 0, 0)
                ->generate($profileUrl);

            $filename = 'qr_' . $member->id . '_' . uniqid() . '.svg';
            $path = 'qr-codes/' . $filename;

            Storage::disk('public')->put($path, (string) $qrSvg);

            // Delete old QR code if exists
            if ($member->qr_code) {
                Storage::disk('public')->delete($member->qr_code);
            }

            $member->updateQuietly(['qr_code' => $path]);

            return;
        } catch (\Exception $e) {
            $this->command->warn("Could not generate QR code for member {$member->name}: {$e->getMessage()}");
        }
    }
}
