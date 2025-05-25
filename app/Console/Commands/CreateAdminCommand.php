<?php
// app/Console/Commands/CreateAdminCommand.php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create {email} {password} {--first-name=} {--last-name=}';
    protected $description = 'Créer un compte administrateur';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $firstName = $this->option('first-name') ?? 'Admin';
        $lastName = $this->option('last-name') ?? 'NigerDev';

        // Validation des données
        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ], [
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        try {
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            $this->info('Compte administrateur créé avec succès !');
            $this->table(
                ['ID', 'Prénom', 'Nom', 'Email', 'Rôle'],
                [[$user->id, $user->first_name, $user->last_name, $user->email, $user->role]]
            );

            return 0;
        } catch (\Exception $e) {
            $this->error('Erreur lors de la création du compte administrateur : ' . $e->getMessage());
            return 1;
        }
    }
}
