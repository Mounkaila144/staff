<x-app-layout>
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                <h1>Gestion des salaires</h1>
                <p class="header-subtitle">NigerDev - Liste des salaires des stagiaires</p>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="tasks-section fade-in">
            <div style="overflow-x: auto;">
                <table class="tasks-table">
                    <thead>
                        <tr>
                            <th>Stagiaire</th>
                            <th>Email</th>
                            <th>Salaire</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interns as $intern)
                            <tr>
                                <td>
                                    <div class="task-title">{{ $intern->full_name }}</div>
                                </td>
                                <td>
                                    <div class="task-description">{{ $intern->email }}</div>
                                </td>
                                <td>
                                    <div class="task-date">200 000 FCFA</div>
                                </td>
                                <td>
                                    <div class="actions-container">
                                        <a href="{{ route('salaire.show', $intern) }}" class="action-btn action-view" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout> 