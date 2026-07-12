<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'make:super-admin';
    protected $description = 'Ensure a super-admin account exists with email superadmin@example.com and password "password"';

    public function handle(): int
    {
        $email = 'superadmin@example.com';
        $password = 'password';
        $name = 'Super Admin';

        $user = User::where('email', $email)->first();

        if (!$user) {
            // Create the account
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            $user->syncRoles(['super-admin']);
            $this->info("Super admin created: {$email}");
            return Command::SUCCESS;
        }

        // Account exists — check if password needs updating
        if (!Hash::check($password, $user->password)) {
            $user->password = Hash::make($password);
            $user->save();

            // Ensure the role is still assigned
            if (!$user->hasRole('super-admin')) {
                $user->syncRoles(['super-admin']);
            }

            $this->warn("Super admin '{$email}' already exists but the password was different.");
            $this->line('The password has been forcefully updated to "password".');
            return Command::SUCCESS;
        }

        // Ensure the role is assigned (in case it was removed somehow)
        if (!$user->hasRole('super-admin')) {
            $user->syncRoles(['super-admin']);
            $this->warn("Super admin '{$email}' existed but was missing the super-admin role. Role has been reassigned.");
            return Command::SUCCESS;
        }

        $this->line("Super admin '{$email}' already exists with the correct password. Nothing to do.");

        return Command::SUCCESS;
    }
}
