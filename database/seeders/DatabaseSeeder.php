<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Créer l'admin
        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Créer quelques stagiaires
        User::factory()->count(5)->create([
            'role' => 'intern'
        ]);

        // Créer des tâches
        $this->call([
            TaskSeeder::class
        ]);
    }
}
