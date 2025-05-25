<x-app-layout>
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                <h1>Salaire de {{ $intern->full_name }}</h1>
                <p class="header-subtitle">NigerDev - Gestion des salaires</p>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="stats-grid fade-in">
            <div class="stat-card total">
                <div class="stat-content">
                    <div class="stat-icon total">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Montant du salaire</h3>
                        <p>200 000 FCFA</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tasks-section fade-in" style="padding:2rem; text-align:center;">
            <h2>Détails du salaire</h2>
            <p>Ce stagiaire reçoit une indemnité mensuelle selon la politique de NigerDev.</p>
        </div>
    </div>
</x-app-layout> 