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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'assigned_to' => 'required_if:' . (Auth::user()->isAdmin() ? 'true' : 'false') . '|exists:users,id'
        ]);

        // Pour les non-admin, assigner la tâche à eux-mêmes
        if (!Auth::user()->isAdmin()) {
            $validated['assigned_to'] = Auth::id();
        }

        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => 'pending',
            'assigned_to' => $validated['assigned_to'],
            'created_by' => Auth::id(),
        ];

        $task = Task::create($taskData);

        // Réponse JSON pour les requêtes AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tâche créée avec succès.',
                'task' => $task->load('assignedUser')
            ]);
        }

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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'assigned_to' => 'required_if:' . (Auth::user()->isAdmin() ? 'true' : 'false') . '|exists:users,id'
        ]);

        // Pour les non-admin, garder l'assignation actuelle
        if (!Auth::user()->isAdmin()) {
            $validated['assigned_to'] = $task->assigned_to;
        }

        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'assigned_to' => $validated['assigned_to'],
        ];

        $task->update($taskData);

        // Réponse JSON pour les requêtes AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tâche mise à jour avec succès.',
                'task' => $task->fresh()->load('assignedUser')
            ]);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();

        // Réponse JSON pour les requêtes AJAX
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tâche supprimée avec succès.'
            ]);
        }

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
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seul un administrateur peut marquer une tâche comme terminée.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Seul un administrateur peut marquer une tâche comme terminée.');
        }

        $task->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : null
        ]);

        // Réponse JSON pour les requêtes AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès.',
                'task' => $task->fresh()->load('assignedUser')
            ]);
        }

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