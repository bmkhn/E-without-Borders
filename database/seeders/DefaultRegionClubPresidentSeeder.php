<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultRegionClubPresidentSeeder extends Seeder
{
    public function run(): void
    {
        // Create the region
        $region = Region::firstOrCreate([
            'name' => 'Palawan Region 8',
        ]);

        // Create the National President (only one)
        $np = User::firstOrCreate(
            [
                'email' => 'np@example.com',
            ],
            [
                'name' => 'National President',
                'password' => Hash::make('password'),
                'club_id' => null, // No club assignment
            ]
        );

        $np->syncRoles(['national-president']);

        // Clubs and their respective Club Presidents
        $clubs = [
            [
                'name' => 'Roxas Pangolin Eagles Club',
                'cp_name' => 'CP - Roxas Pangolin Eagles Club',
                'cp_email' => 'cp.roxas@example.com',
            ],
            [
                'name' => 'Roxas Pangolin Lady Eagles Club',
                'cp_name' => 'CP - Roxas Pangolin Lady Eagles Club',
                'cp_email' => 'cp.roxaslady@example.com',
            ],
            [
                'name' => 'Puerto de Paragua Eagles Club',
                'cp_name' => 'CP - Puerto de Paragua Eagles Club',
                'cp_email' => 'cp.puerto@example.com',
            ],
            [
                'name' => 'Puerto de Paragua Lady Eagles Club',
                'cp_name' => 'CP - Puerto de Paragua Lady Eagles Club',
                'cp_email' => 'cp.puertolady@example.com',
            ],
            [
                'name' => 'The Eagles of Australia Eagles Club',
                'cp_name' => 'CP - The Eagles of Australia Eagles Club',
                'cp_email' => 'cp.australia@example.com',
            ],
        ];

        foreach ($clubs as $data) {
            $club = Club::firstOrCreate(
                [
                    'name' => $data['name'],
                ],
                [
                    'region_id' => $region->id,
                ]
            );

            $cp = User::firstOrCreate(
                [
                    'email' => $data['cp_email'],
                ],
                [
                    'name' => $data['cp_name'],
                    'password' => Hash::make('password'),
                    'club_id' => $club->id,
                ]
            );

            $cp->club_id = $club->id;
            $cp->save();
            $cp->syncRoles(['club-president']);
        }

        $this->command->info('Region, clubs, National President, and Club Presidents seeded successfully.');
    }
}