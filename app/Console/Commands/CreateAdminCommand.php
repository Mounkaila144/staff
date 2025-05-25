<?php
// app/Console/Commands/CreateAdminCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdminCommand extends Command
{
    protected $signature = 'make:admin {--email=} {--password=}';
    protected $description = 'Créer un compte administrateur';

    public function handle()
    {
        $email = $this->option('email') ?: $this->ask('Email de l\'administrateur');
        $password = $this->option('password') ?: $this->secret('Mot de passe');

        if (User::where('email', $email)->exists()) {
            $this->error('Un utilisateur avec cet email existe déjà.');
            return 1;
        }

        $firstName = $this->ask('Prénom');
        $lastName = $this->ask('Nom');

        User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => bcrypt($password),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        $this->info("Administrateur créé avec succès : {$email}");
        return 0;
    }
}
