<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes pour les tâches
    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    
    // Routes pour le profil
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/salaire', [\App\Http\Controllers\SalaireController::class, 'index'])->name('salaire.index');
    Route::get('/salaire/{intern}', [\App\Http\Controllers\SalaireController::class, 'show'])->name('salaire.show');

    // Routes pour les demandes d'autorisation
    Route::get('/autorisation', [\App\Http\Controllers\AutorisationController::class, 'index'])->name('autorisation.index');
    Route::get('/autorisation/create', [\App\Http\Controllers\AutorisationController::class, 'create'])->name('autorisation.create');
    Route::post('/autorisation', [\App\Http\Controllers\AutorisationController::class, 'store'])->name('autorisation.store');

    // Routes pour l'administration (seulement pour les admins)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/interns', [AdminController::class, 'index'])->name('interns.index');
        Route::get('/interns/create', [AdminController::class, 'create'])->name('interns.create');
        Route::post('/interns', [AdminController::class, 'store'])->name('interns.store');
        Route::get('/interns/{intern}/edit', [AdminController::class, 'edit'])->name('interns.edit');
        Route::put('/interns/{intern}', [AdminController::class, 'update'])->name('interns.update');
        Route::delete('/interns/{intern}', [AdminController::class, 'destroy'])->name('interns.destroy');
    });
});

require __DIR__.'/auth.php';
