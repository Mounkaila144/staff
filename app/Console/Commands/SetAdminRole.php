<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetAdminRole extends Command
{
    protected $signature = 'user:set-admin {email}';
    protected $description = 'Définit le rôle admin pour un utilisateur';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Utilisateur non trouvé avec l'email: {$email}");
            return 1;
        }

        $user->update(['role' => User::ROLE_ADMIN]);
        $this->info("Le rôle admin a été défini pour {$email}");
        return 0;
    }
} 