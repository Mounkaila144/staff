<x-app-layout>
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                <h1>Nouvelle demande d'autorisation</h1>
                <p class="header-subtitle">NigerDev - Créer une nouvelle demande d'autorisation</p>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="tasks-section fade-in">
            <form action="{{ route('autorisation.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="form-group">
                    <label for="motif" class="form-label">Motif de l'autorisation</label>
                    <textarea name="motif" id="motif" rows="3" class="form-input" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="commentaire" class="form-label">Commentaire additionnel</label>
                    <textarea name="commentaire" id="commentaire" rows="3" class="form-input"></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('autorisation.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 