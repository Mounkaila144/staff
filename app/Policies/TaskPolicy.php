<?php
// app/Policies/TaskPolicy.php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;
    
    public function view(User $user, Task $task)
    {
        return $user->isAdmin() || $task->assigned_to === $user->id;
    }

    public function update(User $user, Task $task)
    {
        return $user->isAdmin() || $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task)
    {
        return $user->isAdmin();
    }
}
