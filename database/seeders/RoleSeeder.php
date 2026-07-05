<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(
            ['name' => 'national-president', 'guard_name' => 'web'],
            []
        );

        Role::updateOrCreate(
            ['name' => 'club-president', 'guard_name' => 'web'],
            []
        );
    }
}
