<?php
// app/Console/Commands/CreateAdminCommand.php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create {email} {password} {--name=}';
    protected $description = 'Créer un compte administrateur';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->option('name') ?? 'Administrateur';

        // Validation des données
        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
            'name' => $name,
        ], [
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        try {
            $user = User::create([
                'full_name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);

            $this->info('Compte administrateur créé avec succès !');
            $this->table(
                ['ID', 'Nom', 'Email', 'Rôle'],
                [[$user->id, $user->full_name, $user->email, $user->role]]
            );

            return 0;
        } catch (\Exception $e) {
            $this->error('Erreur lors de la création du compte administrateur : ' . $e->getMessage());
            return 1;
        }
    }
}
