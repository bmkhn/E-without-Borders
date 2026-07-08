<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'super-admin',
            'national-admin',
            'regional-admin',
            'club-admin',
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role, 'guard_name' => 'web'],
                []
            );
        }

        // Remove old role names if they exist
        foreach (['national-president', 'club-president'] as $oldRole) {
            Role::where('name', $oldRole)->where('guard_name', 'web')->delete();
        }
    }
}
