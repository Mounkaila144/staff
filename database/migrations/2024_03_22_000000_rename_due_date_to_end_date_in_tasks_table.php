<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Supprimer la colonne end_date si elle existe
            if (Schema::hasColumn('tasks', 'end_date')) {
                $table->dropColumn('end_date');
            }
            // Renommer due_date en end_date
            $table->renameColumn('due_date', 'end_date');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Renommer end_date en due_date
            $table->renameColumn('end_date', 'due_date');
        });
    }
}; 