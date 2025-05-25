<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Créer une nouvelle table temporaire avec la structure mise à jour
            DB::statement('CREATE TABLE tasks_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR NOT NULL,
                description TEXT NULL,
                start_date DATETIME NULL,
                end_date DATETIME NOT NULL,
                priority VARCHAR CHECK(priority IN (\'low\', \'medium\', \'high\')) NOT NULL DEFAULT \'medium\',
                status VARCHAR CHECK(status IN (\'pending\', \'in_progress\', \'completed\', \'for_validation\')) NOT NULL DEFAULT \'pending\',
                assigned_to INTEGER NOT NULL,
                created_by INTEGER NOT NULL,
                completed_at DATETIME NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                FOREIGN KEY(assigned_to) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE CASCADE
            )');

            // Copier les données
            DB::statement('INSERT INTO tasks_new SELECT * FROM tasks');

            // Supprimer l\'ancienne table
            DB::statement('DROP TABLE tasks');

            // Renommer la nouvelle table
            DB::statement('ALTER TABLE tasks_new RENAME TO tasks');

            // Recréer les index
            DB::statement('CREATE INDEX tasks_assigned_to_status_end_date_index ON tasks (assigned_to, status, end_date)');
            DB::statement('CREATE INDEX tasks_created_by_created_at_index ON tasks (created_by, created_at)');
        } else {
            Schema::table('tasks', function (Blueprint $table) {
                DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'for_validation') NOT NULL DEFAULT 'pending'");
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Créer une nouvelle table temporaire avec l'ancienne structure
            DB::statement('CREATE TABLE tasks_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR NOT NULL,
                description TEXT NULL,
                start_date DATETIME NULL,
                end_date DATETIME NOT NULL,
                priority VARCHAR CHECK(priority IN (\'low\', \'medium\', \'high\')) NOT NULL DEFAULT \'medium\',
                status VARCHAR CHECK(status IN (\'pending\', \'in_progress\', \'completed\')) NOT NULL DEFAULT \'pending\',
                assigned_to INTEGER NOT NULL,
                created_by INTEGER NOT NULL,
                completed_at DATETIME NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                FOREIGN KEY(assigned_to) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE CASCADE
            )');

            // Copier les données en convertissant for_validation à completed
            DB::statement("INSERT INTO tasks_new 
                SELECT id, title, description, start_date, end_date, priority, 
                CASE WHEN status = 'for_validation' THEN 'completed' ELSE status END AS status, 
                assigned_to, created_by, completed_at, created_at, updated_at 
                FROM tasks");

            // Supprimer l'ancienne table
            DB::statement('DROP TABLE tasks');

            // Renommer la nouvelle table
            DB::statement('ALTER TABLE tasks_new RENAME TO tasks');

            // Recréer les index
            DB::statement('CREATE INDEX tasks_assigned_to_status_end_date_index ON tasks (assigned_to, status, end_date)');
            DB::statement('CREATE INDEX tasks_created_by_created_at_index ON tasks (created_by, created_at)');
        } else {
            Schema::table('tasks', function (Blueprint $table) {
                DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending'");
            });
        }
    }
}; 