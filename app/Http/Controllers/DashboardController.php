<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        // Filtres pour les administrateurs
        if (auth()->user()->isAdmin()) {
            if ($request->filled('intern_id')) {
                $query->where('assigned_to', $request->intern_id);
            }
        } else {
            // Pour les stagiaires, ne montrer que leurs tâches
            $query->where('assigned_to', auth()->id());
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par date d'échéance
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', $request->end_date);
        }

        // Récupérer les tâches avec pagination
        $tasks = $query->with('assignedUser')->latest()->paginate(10);

        // Statistiques
        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();
        $inProgressTasks = Task::where('status', 'in_progress')->count();

        // Liste des stagiaires pour le filtre
        $interns = User::where('role', 'intern')->get();

        return view('dashboard', compact(
            'tasks',
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'inProgressTasks',
            'interns'
        ));
    }

    private function adminDashboard()
    {
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $totalInterns = User::where('role', 'intern')->count();

        $recentTasks = Task::with(['assignedUser', 'creator'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalTasks', 'completedTasks', 'pendingTasks',
            'totalInterns', 'recentTasks'
        ));
    }

    private function internDashboard()
    {
        $user = auth()->user();

        $todayTasks = Task::forUser($user->id)
            ->today()
            ->with('creator')
            ->orderBy('priority', 'desc')
            ->orderBy('end_date', 'asc')
            ->get();

        $stats = [
            'total' => Task::forUser($user->id)->count(),
            'completed' => Task::forUser($user->id)->byStatus('completed')->count(),
            'pending' => Task::forUser($user->id)->byStatus('pending')->count(),
            'in_progress' => Task::forUser($user->id)->byStatus('in_progress')->count(),
        ];

        return view('dashboard.intern', compact('todayTasks', 'stats'));
    }
}
