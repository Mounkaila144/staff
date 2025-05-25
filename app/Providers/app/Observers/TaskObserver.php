<?php
// app/Observers/TaskObserver.php

class TaskObserver
{
    public function updating(Task $task)
    {
        // Si le statut passe à "terminé", enregistrer la date
        if ($task->isDirty('status') && $task->status === 'completed') {
            $task->completed_at = now();
        }

        // Si le statut change depuis "terminé", effacer la date
        if ($task->isDirty('status') && $task->getOriginal('status') === 'completed' && $task->status !== 'completed') {
            $task->completed_at = null;
        }
    }

    public function created(Task $task)
    {
        // Log de création de tâche
        Log::info('Nouvelle tâche créée', [
            'task_id' => $task->id,
            'title' => $task->title,
            'assigned_to' => $task->assignedUser->email,
            'created_by' => $task->creator->email
        ]);
    }
}
