<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdminCommand extends Command
{
    protected $signature = 'make:admin {--email=} {--password=}';
    protected $description = 'Create an admin user';

    public function handle()
    {
        $email = $this->option('email') ?? $this->ask('What is the admin email?');
        $password = $this->option('password') ?? $this->secret('What is the admin password?');

        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->info('Admin user created successfully!');
        $this->table(
            ['Email', 'Role'],
            [[$user->email, $user->role]]
        );
    }
} 