<?php
// database/seeders/UserSeeder.php

class UserSeeder extends Seeder
{
    public function run()
    {
        // Créer un administrateur par défaut
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@tasktracker.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // Créer des stagiaires de test
        $interns = [
            ['first_name' => 'Jean', 'last_name' => 'Dupont', 'email' => 'jean.dupont@example.com'],
            ['first_name' => 'Marie', 'last_name' => 'Martin', 'email' => 'marie.martin@example.com'],
            ['first_name' => 'Pierre', 'last_name' => 'Bernard', 'email' => 'pierre.bernard@example.com'],
            ['first_name' => 'Sophie', 'last_name' => 'Dubois', 'email' => 'sophie.dubois@example.com'],
            ['first_name' => 'Lucas', 'last_name' => 'Moreau', 'email' => 'lucas.moreau@example.com']
        ];

        foreach ($interns as $intern) {
            User::create([
                'first_name' => $intern['first_name'],
                'last_name' => $intern['last_name'],
                'email' => $intern['email'],
                'password' => bcrypt('password'),
                'role' => 'intern',
                'email_verified_at' => now()
            ]);
        }
    }
}
