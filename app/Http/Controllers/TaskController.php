<?php
// app/Http/Controllers/TaskController.php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('assignedUser')
            ->when(Auth::user()->isAdmin(), function ($query) {
                return $query->latest();
            }, function ($query) {
                return $query->where('assigned_to', Auth::id())->latest();
            })
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::where('role', 'intern')->get();
        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,in_progress,completed,for_validation',
            'user_id' => 'required|exists:users,id'
        ]);
    
        // ⚠️ NE PAS utiliser directement $validated
        // Car il contient 'user_id' mais la DB attend 'assigned_to'
        
        // ✅ Mapper correctement les données
        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'assigned_to' => $validated['user_id'], // 🔄 Mapper user_id vers assigned_to
            'created_by' => Auth::id(), // 🔄 Ajouter l'utilisateur qui crée la tâche
        ];
    
        $task = Task::create($taskData); // ✅ Utiliser les données mappées
    
        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tâche créée avec succès.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $users = User::where('role', 'intern')->get();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,in_progress,completed,for_validation',
            'user_id' => 'required|exists:users,id'
        ]);

        // Mapper user_id vers assigned_to
        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'assigned_to' => $validated['user_id'],
        ];

        $task->update($taskData);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Tâche supprimée avec succès.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,for_validation'
        ]);

        // Empêcher les stagiaires de mettre le statut à "Terminé"
        if (!$request->user()->isAdmin() && $request->status === 'completed') {
            return redirect()->back()->with('error', 'Seul un administrateur peut marquer une tâche comme terminée.');
        }

        $task->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : null
        ]);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès');
    }

    public function history()
    {
        $user = auth()->user();

        $completedTasks = Task::forUser($user->id)
            ->byStatus('completed')
            ->with('creator')
            ->latest('completed_at')
            ->paginate(15);

        return view('tasks.history', compact('completedTasks'));
    }
}