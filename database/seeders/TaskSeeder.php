<?php
// database/seeders/TaskSeeder.php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        $interns = User::where('role', 'intern')->get();

        $taskTemplates = [
            ['title' => 'Révision du code PHP', 'description' => 'Revoir et optimiser le code du module utilisateur', 'priority' => 'high'],
            ['title' => 'Documentation API', 'description' => 'Rédiger la documentation des endpoints API', 'priority' => 'medium'],
            ['title' => 'Tests unitaires', 'description' => 'Écrire des tests pour les nouvelles fonctionnalités', 'priority' => 'high'],
            ['title' => 'Intégration Bootstrap', 'description' => 'Mise à jour de l\'interface avec Bootstrap 5', 'priority' => 'low'],
            ['title' => 'Optimisation base de données', 'description' => 'Analyser et optimiser les requêtes lentes', 'priority' => 'medium'],
            ['title' => 'Formation Laravel', 'description' => 'Suivre le tutoriel Laravel avancé', 'priority' => 'low'],
            ['title' => 'Debug formulaire contact', 'description' => 'Corriger le bug de validation du formulaire', 'priority' => 'high'],
            ['title' => 'Mise à jour dépendances', 'description' => 'Mettre à jour Composer et NPM packages', 'priority' => 'medium']
        ];

        foreach ($interns as $intern) {
            // Créer 4-6 tâches par stagiaire
            $taskCount = rand(4, 6);

            for ($i = 0; $i < $taskCount; $i++) {
                $template = $taskTemplates[array_rand($taskTemplates)];
                $dueDate = now()->addDays(rand(-2, 7)); // Tâches du passé au futur
                $status = $this->getRandomStatus($dueDate);

                Task::create([
                    'title' => $template['title'] . ' - ' . $intern->first_name,
                    'description' => $template['description'],
                    'end_date' => $dueDate,
                    'priority' => $template['priority'],
                    'status' => $status,
                    'assigned_to' => $intern->id,
                    'created_by' => $admin->id,
                    'completed_at' => $status === 'completed' ? $dueDate->subHours(rand(1, 24)) : null
                ]);
            }
        }
    }

    private function getRandomStatus($dueDate)
    {
        if ($dueDate->isPast()) {
            return ['completed', 'in_progress'][rand(0, 1)];
        }

        return ['pending', 'in_progress', 'completed'][rand(0, 2)];
    }
}
