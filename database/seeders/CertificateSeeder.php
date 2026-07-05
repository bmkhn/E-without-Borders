<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Member;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::all();

        if ($members->isEmpty()) {
            $this->command->warn('No members found. Skipping Certificate seeder.');
            return;
        }

        $certificateTemplates = [
            ['name' => 'Leadership Excellence Award', 'offset' => 0],
            ['name' => 'Community Service Recognition', 'offset' => 1],
            ['name' => 'Fraternal Service Award', 'offset' => 2],
        ];

        $created = 0;

        foreach ($members as $member) {
            $count = rand(1, 3);

            for ($i = 0; $i < $count && $i < count($certificateTemplates); $i++) {
                $template = $certificateTemplates[$i];

                // Avoid duplicates on re-seed
                $existing = Certificate::where('member_id', $member->id)
                    ->where('name', $template['name'])
                    ->exists();

                if ($existing) {
                    continue;
                }

                Certificate::create([
                    'member_id' => $member->id,
                    'name' => $template['name'],
                    'issued_at' => now()->subMonths(rand(1, 12))->subDays(rand(0, 28)),
                ]);

                $created++;
            }
        }

        $this->command->info("Created {$created} certificates across {$members->count()} members.");
    }
}
