<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function __construct()
    {
        // Les middlewares sont déjà définis dans les routes
    }

    public function index()
    {
        $interns = User::where('role', 'intern')->latest()->paginate(10);
        return view('admin.interns.index', compact('interns'));
    }

    public function create()
    {
        return view('admin.interns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'intern',
            'is_active' => true
        ]);

        return redirect()->route('admin.interns.index')
            ->with('success', 'Stagiaire créé avec succès.');
    }

    public function edit(User $intern)
    {
        return view('admin.interns.edit', compact('intern'));
    }

    public function update(Request $request, User $intern)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $intern->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $intern->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $intern->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.interns.index')
            ->with('success', 'Stagiaire mis à jour avec succès.');
    }

    public function destroy(User $intern)
    {
        $intern->delete();

        return redirect()->route('admin.interns.index')
            ->with('success', 'Stagiaire supprimé avec succès.');
    }

    // Gestion des tâches
    public function tasks()
    {
        $tasks = Task::with(['assignedUser', 'creator'])
            ->when(request('intern'), function($query) {
                $query->where('assigned_to', request('intern'));
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->latest()
            ->paginate(15);

        $interns = User::where('role', 'intern')->get();

        return view('admin.tasks.index', compact('tasks', 'interns'));
    }

    public function createTask()
    {
        $interns = User::where('role', 'intern')->where('is_active', true)->get();
        return view('admin.tasks.create', compact('interns'));
    }

    public function storeTask(StoreTaskRequest $request)
    {
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'end_date' => $request->end_date,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Tâche créée avec succès');
    }
}
