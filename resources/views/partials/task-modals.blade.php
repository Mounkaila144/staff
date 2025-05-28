<!-- Modal Overlay -->
<div id="modalOverlay" class="modal-overlay" onclick="closeAllModals()"></div>

<!-- View Task Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-eye"></i>
                Détails de la tâche
            </h2>
            <button onclick="closeModal('viewModal')" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="task-details">
                <div class="detail-group">
                    <label>Titre</label>
                    <div id="viewTitle" class="detail-value"></div>
                </div>
                <div class="detail-group">
                    <label>Description</label>
                    <div id="viewDescription" class="detail-value"></div>
                </div>
                <div class="detail-group">
                    <label>Statut</label>
                    <div id="viewStatus" class="detail-value"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-group">
                        <label>Date de début</label>
                        <div id="viewStartDate" class="detail-value"></div>
                    </div>
                    <div class="detail-group">
                        <label>Date d'échéance</label>
                        <div id="viewEndDate" class="detail-value"></div>
                    </div>
                </div>
                <div id="viewAssignedGroup" class="detail-group">
                    <label>Assigné à</label>
                    <div id="viewAssigned" class="detail-value"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-group">
                        <label>Créé le</label>
                        <div id="viewCreatedAt" class="detail-value"></div>
                    </div>
                    <div class="detail-group">
                        <label>Modifié le</label>
                        <div id="viewUpdatedAt" class="detail-value"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeModal('viewModal')" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Fermer
            </button>
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-plus"></i>
                Créer une nouvelle tâche
            </h2>
            <button onclick="closeModal('createModal')" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="createTaskForm" onsubmit="submitCreateTask(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label for="createTitle">Titre *</label>
                    <input type="text" id="createTitle" name="title" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="createDescription">Description *</label>
                    <textarea id="createDescription" name="description" class="form-textarea" rows="4" required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="createStartDate">Date de début</label>
                        <input type="date" id="createStartDate" name="start_date" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="createEndDate">Date d'échéance</label>
                        <input type="date" id="createEndDate" name="end_date" class="form-input">
                    </div>
                </div>
                <div id="createAssignedGroup" class="form-group">
                    <label for="createAssignedTo">Assigné à *</label>
                    <select id="createAssignedTo" name="assigned_to" class="form-select" required>
                        <option value="">Sélectionner un stagiaire</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('createModal')" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Créer la tâche
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Task Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-edit"></i>
                Modifier la tâche
            </h2>
            <button onclick="closeModal('editModal')" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editTaskForm" onsubmit="submitEditTask(event)">
            <input type="hidden" id="editTaskId" name="task_id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="editTitle">Titre *</label>
                    <input type="text" id="editTitle" name="title" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="editDescription">Description *</label>
                    <textarea id="editDescription" name="description" class="form-textarea" rows="4" required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editStartDate">Date de début</label>
                        <input type="date" id="editStartDate" name="start_date" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="editEndDate">Date d'échéance</label>
                        <input type="date" id="editEndDate" name="end_date" class="form-input">
                    </div>
                </div>
                <div id="editAssignedGroup" class="form-group">
                    <label for="editAssignedTo">Assigné à *</label>
                    <select id="editAssignedTo" name="assigned_to" class="form-select" required>
                        <option value="">Sélectionner un stagiaire</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('editModal')" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Status Change Modal -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-exchange-alt"></i>
                Changer le statut
            </h2>
            <button onclick="closeModal('statusModal')" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="status-confirmation">
                <div class="status-icon">
                    <i id="statusIcon" class="fas"></i>
                </div>
                <p id="statusMessage" class="status-message"></p>
                <div class="task-info">
                    <strong id="statusTaskTitle"></strong>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeModal('statusModal')" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Annuler
            </button>
            <button onclick="confirmStatusChange()" class="btn btn-primary" id="statusConfirmBtn">
                <i class="fas fa-check"></i>
                Confirmer
            </button>
        </div>
    </div>
</div>

<!-- Delete Task Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-trash"></i>
                Supprimer la tâche
            </h2>
            <button onclick="closeModal('deleteModal')" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="delete-confirmation">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="delete-message">
                    Êtes-vous sûr de vouloir supprimer cette tâche ?
                </p>
                <div class="task-info">
                    <strong id="deleteTaskTitle"></strong>
                </div>
                <p class="delete-warning">
                    Cette action est irréversible.
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeModal('deleteModal')" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Annuler
            </button>
            <button onclick="confirmDelete()" class="btn btn-danger">
                <i class="fas fa-trash"></i>
                Supprimer
            </button>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="modal">
    <div class="modal-content loading-content">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
        <p class="loading-text">Traitement en cours...</p>
    </div>
</div> 