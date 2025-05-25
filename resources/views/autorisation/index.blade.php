<x-app-layout>
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                <h1>Demandes d'autorisation</h1>
                <p class="header-subtitle">NigerDev - Gestion des demandes d'autorisation</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('autorisation.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle demande
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="tasks-section fade-in">
            <div style="overflow-x: auto;">
                <table class="tasks-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($autorisations as $autorisation)
                            <tr>
                                <td>
                                    <div class="task-date">{{ $autorisation->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    <div class="task-title">{{ $autorisation->motif }}</div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($autorisation->statut) }}">
                                        {{ $autorisation->statut }}
                                    </span>
                                </td>
                                <td>
                                    <div class="task-date">{{ $autorisation->date_debut->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    <div class="task-date">{{ $autorisation->date_fin->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    <div class="actions-container">
                                        <a href="#" class="action-btn action-view" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2"></i>
                                        <p>Aucune demande d'autorisation trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout> 