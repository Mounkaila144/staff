<?php
// app/Console/Commands/TaskCleanupCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TaskCleanupCommand extends Command
{
    protected $signature = 'tasks:cleanup {--days=30}';
    protected $description = 'Nettoyer les anciennes tâches terminées';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);

        $count = Task::where('status', 'completed')
            ->where('completed_at', '<', $cutoffDate)
            ->count();

        if ($count === 0) {
            $this->info('Aucune tâche à nettoyer.');
            return 0;
        }

        if ($this->confirm("Supprimer {$count} tâche(s) terminée(s) depuis plus de {$days} jours ?")) {
            Task::where('status', 'completed')
                ->where('completed_at', '<', $cutoffDate)
                ->delete();

            $this->info("{$count} tâche(s) supprimée(s) avec succès.");
        }

        return 0;
    }
}
