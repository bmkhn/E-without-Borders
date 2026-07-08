<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'admin:create-super {name?} {email?} {password?}';
    protected $description = 'Create a super-admin user';

    public function handle(): int
    {
        $name = $this->argument('name') ?? $this->ask('Name', 'Super Admin');
        $email = $this->argument('email') ?? $this->ask('Email', 'superadmin@example.com');
        $password = $this->argument('password') ?? $this->secret('Password');

        if (!$password) {
            $password = 'password';
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->syncRoles(['super-admin']);

        $this->info("Super admin created: {$user->email}");

        return Command::SUCCESS;
    }
}
