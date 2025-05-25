<?php
// app/Providers/TaskServiceProvider.php

class TaskServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Enregistrer les policies
        Gate::define('manage-interns', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->isAdmin();
        });

        // Boot des observers
        Task::observe(TaskObserver::class);
        User::observe(UserObserver::class);
    }

    public function register()
    {
        $this->app->singleton('task.manager', function ($app) {
            return new TaskManager();
        });
    }
}
