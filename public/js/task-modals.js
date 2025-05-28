// Global variables
let currentTaskId = null;
let currentStatus = null;

// Initialize modals when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeModals();
});

function initializeModals() {
    // Populate user selects for admin
    if (window.isAdmin) {
        populateUserSelects();
    } else {
        // Hide assigned fields for non-admin users
        hideAssignedFields();
    }
}

function populateUserSelects() {
    const createSelect = document.getElementById('createAssignedTo');
    const editSelect = document.getElementById('editAssignedTo');
    
    if (window.usersData && createSelect && editSelect) {
        window.usersData.forEach(user => {
            const createOption = new Option(user.name, user.id);
            const editOption = new Option(user.name, user.id);
            createSelect.add(createOption);
            editSelect.add(editOption);
        });
    }
}

function hideAssignedFields() {
    const createAssignedGroup = document.getElementById('createAssignedGroup');
    const editAssignedGroup = document.getElementById('editAssignedGroup');
    const viewAssignedGroup = document.getElementById('viewAssignedGroup');
    
    if (createAssignedGroup) createAssignedGroup.style.display = 'none';
    if (editAssignedGroup) editAssignedGroup.style.display = 'none';
    if (viewAssignedGroup) viewAssignedGroup.style.display = 'none';
}

// Modal management functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('modalOverlay');
    
    if (modal && overlay) {
        overlay.style.display = 'block';
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Add animation
        setTimeout(() => {
            overlay.classList.add('active');
            modal.classList.add('active');
        }, 10);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('modalOverlay');
    
    if (modal && overlay) {
        overlay.classList.remove('active');
        modal.classList.remove('active');
        
        setTimeout(() => {
            overlay.style.display = 'none';
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
}

function closeAllModals() {
    const modals = document.querySelectorAll('.modal');
    const overlay = document.getElementById('modalOverlay');
    
    overlay.classList.remove('active');
    modals.forEach(modal => {
        modal.classList.remove('active');
    });
    
    setTimeout(() => {
        overlay.style.display = 'none';
        modals.forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.style.overflow = 'auto';
    }, 300);
}

// Task-specific modal functions
function openViewModal(taskId) {
    const task = getTaskById(taskId);
    if (!task) return;
    
    // Populate view modal
    document.getElementById('viewTitle').textContent = task.title;
    document.getElementById('viewDescription').textContent = task.description;
    document.getElementById('viewStatus').innerHTML = getStatusBadge(task.status);
    document.getElementById('viewStartDate').textContent = task.start_date ? formatDate(task.start_date) : 'Non définie';
    document.getElementById('viewEndDate').textContent = task.end_date ? formatDate(task.end_date) : 'Non définie';
    document.getElementById('viewCreatedAt').textContent = task.created_at;
    document.getElementById('viewUpdatedAt').textContent = task.updated_at;
    
    if (window.isAdmin && task.assigned_user) {
        document.getElementById('viewAssigned').textContent = task.assigned_user;
    }
    
    openModal('viewModal');
}

function openCreateTaskModal() {
    // Reset form
    document.getElementById('createTaskForm').reset();
    
    // Set default assigned user for non-admin
    if (!window.isAdmin) {
        // For non-admin users, we'll handle this in the backend
    }
    
    openModal('createModal');
}

function openEditModal(taskId) {
    const task = getTaskById(taskId);
    if (!task) return;
    
    // Populate edit modal
    document.getElementById('editTaskId').value = task.id;
    document.getElementById('editTitle').value = task.title;
    document.getElementById('editDescription').value = task.description;
    document.getElementById('editStartDate').value = task.start_date || '';
    document.getElementById('editEndDate').value = task.end_date || '';
    
    if (window.isAdmin) {
        document.getElementById('editAssignedTo').value = task.assigned_to;
    }
    
    openModal('editModal');
}

function openStatusModal(taskId, newStatus) {
    const task = getTaskById(taskId);
    if (!task) return;
    
    currentTaskId = taskId;
    currentStatus = newStatus;
    
    // Update status modal content
    const statusInfo = getStatusInfo(newStatus);
    document.getElementById('statusIcon').className = `fas ${statusInfo.icon}`;
    document.getElementById('statusMessage').textContent = `Voulez-vous changer le statut de cette tâche vers "${statusInfo.label}" ?`;
    document.getElementById('statusTaskTitle').textContent = task.title;
    
    const confirmBtn = document.getElementById('statusConfirmBtn');
    confirmBtn.className = `btn ${statusInfo.btnClass}`;
    
    openModal('statusModal');
}

function openDeleteModal(taskId) {
    const task = getTaskById(taskId);
    if (!task) return;
    
    currentTaskId = taskId;
    document.getElementById('deleteTaskTitle').textContent = task.title;
    
    openModal('deleteModal');
}

// Form submission functions
function submitCreateTask(event) {
    event.preventDefault();
    showLoading();
    
    const formData = new FormData(event.target);
    
    // Add assigned_to for non-admin users (assign to themselves)
    if (!window.isAdmin) {
        // This will be handled in the backend
    }
    
    fetch('/tasks', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showSuccessMessage('Tâche créée avec succès');
            closeModal('createModal');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showErrorMessage(data.message || 'Erreur lors de la création de la tâche');
        }
    })
    .catch(error => {
        hideLoading();
        showErrorMessage('Erreur lors de la création de la tâche');
        console.error('Error:', error);
    });
}

function submitEditTask(event) {
    event.preventDefault();
    showLoading();
    
    const formData = new FormData(event.target);
    const taskId = document.getElementById('editTaskId').value;
    
    fetch(`/tasks/${taskId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showSuccessMessage('Tâche modifiée avec succès');
            closeModal('editModal');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showErrorMessage(data.message || 'Erreur lors de la modification de la tâche');
        }
    })
    .catch(error => {
        hideLoading();
        showErrorMessage('Erreur lors de la modification de la tâche');
        console.error('Error:', error);
    });
}

function confirmStatusChange() {
    if (!currentTaskId || !currentStatus) return;
    
    showLoading();
    
    fetch(`/tasks/${currentTaskId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            status: currentStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showSuccessMessage('Statut mis à jour avec succès');
            closeModal('statusModal');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showErrorMessage(data.message || 'Erreur lors de la mise à jour du statut');
        }
    })
    .catch(error => {
        hideLoading();
        showErrorMessage('Erreur lors de la mise à jour du statut');
        console.error('Error:', error);
    });
}

function confirmDelete() {
    if (!currentTaskId) return;
    
    showLoading();
    
    fetch(`/tasks/${currentTaskId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showSuccessMessage('Tâche supprimée avec succès');
            closeModal('deleteModal');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showErrorMessage(data.message || 'Erreur lors de la suppression de la tâche');
        }
    })
    .catch(error => {
        hideLoading();
        showErrorMessage('Erreur lors de la suppression de la tâche');
        console.error('Error:', error);
    });
}

// Utility functions
function getTaskById(taskId) {
    return window.tasksData.find(task => task.id == taskId);
}

function getStatusInfo(status) {
    const statusMap = {
        'pending': {
            label: 'En attente',
            icon: 'fa-clock',
            btnClass: 'btn-warning'
        },
        'in_progress': {
            label: 'En cours',
            icon: 'fa-spinner',
            btnClass: 'btn-info'
        },
        'completed': {
            label: 'Terminée',
            icon: 'fa-check-circle',
            btnClass: 'btn-success'
        },
        'for_validation': {
            label: 'En validation',
            icon: 'fa-clipboard-check',
            btnClass: 'btn-primary'
        }
    };
    
    return statusMap[status] || statusMap['pending'];
}

function getStatusBadge(status) {
    const statusInfo = getStatusInfo(status);
    return `<span class="status-badge status-${status}">
        <i class="status-icon fas ${statusInfo.icon}"></i>
        ${statusInfo.label}
    </span>`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR');
}

function showLoading() {
    openModal('loadingModal');
}

function hideLoading() {
    closeModal('loadingModal');
}

function showSuccessMessage(message) {
    // Create and show success notification
    const notification = document.createElement('div');
    notification.className = 'notification success';
    notification.innerHTML = `
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

function showErrorMessage(message) {
    // Create and show error notification
    const notification = document.createElement('div');
    notification.className = 'notification error';
    notification.innerHTML = `
        <i class="fas fa-exclamation-circle"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAllModals();
    }
});

// Prevent modal content click from closing modal
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-content') || 
        event.target.closest('.modal-content')) {
        event.stopPropagation();
    }
}); 