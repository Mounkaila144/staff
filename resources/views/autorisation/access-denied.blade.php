<x-app-layout>
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                <h1>Accès refusé</h1>
                <p class="header-subtitle">NigerDev - Demande d'autorisation</p>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="tasks-section fade-in">
            <div class="text-center py-12">
                <div class="mb-6">
                    <i class="fas fa-lock text-6xl text-red-500"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    Vous n'avez pas accès à cette option en tant que stagiaire
                </h2>
                <p class="text-gray-600 mb-8">
                    Si vous pensez qu'il s'agit d'une erreur, contactez un administrateur NigerDev.
                </p>
                <a href="{{ route('dashboard') }}" class="btn-primary">
                    <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                </a>
            </div>
        </div>
    </div>
</x-app-layout> 