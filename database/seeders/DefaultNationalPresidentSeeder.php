<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultNationalPresidentSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'np@example.com';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'National President',
                'password' => Hash::make('password'),
            ]
        );

        $user->syncRoles(['national-president']);
    }
}
