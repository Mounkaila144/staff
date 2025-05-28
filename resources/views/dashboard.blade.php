<x-app-layout>
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                <h1>Tableau de bord</h1>
                <p class="header-subtitle">NigerDev - Gestion des tâches</p>
            </div>
            <button onclick="openCreateTaskModal()" class="add-task-btn">
                <i class="fas fa-plus"></i>
                Nouvelle tâche
            </button>
        </div>
    </div>

    <div class="main-content">
        @if (session('success'))
            <div class="success-alert fade-in">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="stats-grid fade-in">
            <div class="stat-card total">
                <div class="stat-content">
                    <div class="stat-icon total">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total des tâches</h3>
                        <p>{{ $totalTasks }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card pending">
                <div class="stat-content">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>En attente</h3>
                        <p>{{ $pendingTasks }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card completed">
                <div class="stat-content">
                    <div class="stat-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Complétées</h3>
                        <p>{{ $completedTasks }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card progress">
                <div class="stat-content">
                    <div class="stat-icon progress">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="stat-info">
                        <h3>En cours</h3>
                        <p>{{ $inProgressTasks }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Filters -->
        @if(auth()->user()->isAdmin())
            <div class="filters-section fade-in">
                <form action="{{ route('dashboard') }}" method="GET" class="filters-grid">
                    <div class="filter-group">
                        <label for="intern">Stagiaire</label>
                        <select name="intern_id" id="intern" class="filter-input">
                            <option value="">Tous les stagiaires</option>
                            @foreach($interns as $intern)
                                <option value="{{ $intern->id }}" {{ request('intern_id') == $intern->id ? 'selected' : '' }}>
                                    {{ $intern->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="status">Statut</label>
                        <select name="status" id="status" class="filter-input">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                            <option value="for_validation" {{ request('status') == 'for_validation' ? 'selected' : '' }}>En validation</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="end_date">Date d'échéance</label>
                        <input type="date" name="end_date" id="end_date" class="filter-input" value="{{ request('end_date') }}">
                    </div>

                    <div class="filter-buttons">
                        <button type="submit" class="filter-btn primary">
                            <i class="fas fa-filter"></i>
                            Filtrer
                        </button>
                        <a href="{{ route('dashboard') }}" class="filter-btn secondary">
                            <i class="fas fa-times"></i>
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        @endif

        <!-- Tasks Section -->
        <div class="tasks-section fade-in">
            @if($tasks->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>Aucune tâche trouvée</h3>
                    <p>Commencez par créer une nouvelle tâche</p>
                </div>
            @else
                <!-- Desktop Table View -->
                <div class="desktop-table">
                    <div class="table-container">
                        <table class="tasks-table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Statut</th>
                                    <th>Date de début</th>
                                    <th>Date d'échéance</th>
                                    @if(auth()->user()->isAdmin())
                                        <th>Assigné à</th>
                                    @endif
                                    <th class="actions-header">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr class="task-row task-row-{{ $task->status }}" data-status="{{ $task->status }}">
                                        <td>
                                            <div class="task-title">{{ $task->title }}</div>
                                        </td>
                                        <td>
                                            <div class="task-description">{{ Str::limit($task->description, 100) }}</div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $task->status }}">
                                                <i class="status-icon fas 
                                                    @switch($task->status)
                                                        @case('pending') fa-clock @break
                                                        @case('in_progress') fa-spinner @break
                                                        @case('completed') fa-check-circle @break
                                                        @case('for_validation') fa-clipboard-check @break
                                                    @endswitch
                                                "></i>
                                                @switch($task->status)
                                                    @case('pending') En attente @break
                                                    @case('in_progress') En cours @break
                                                    @case('completed') Terminée @break
                                                    @case('for_validation') En validation @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <div class="task-date">{{ $task->start_date ? $task->start_date->format('d/m/Y') : 'Non définie' }}</div>
                                        </td>
                                        <td>
                                            <div class="task-date">{{ $task->end_date ? $task->end_date->format('d/m/Y') : 'Non définie' }}</div>
                                        </td>
                                        @if(auth()->user()->isAdmin())
                                            <td>
                                                <div class="task-user">{{ $task->assignedUser->full_name }}</div>
                                            </td>
                                        @endif
                                        <td>
                                            <div class="actions-container">
                                                <button onclick="openViewModal({{ $task->id }})" class="action-btn action-view" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button onclick="openEditModal({{ $task->id }})" class="action-btn action-edit" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if($task->status !== 'pending')
                                                    <button onclick="openStatusModal({{ $task->id }}, 'pending')" class="action-btn action-pending" title="Marquer comme en attente">
                                                        <i class="fas fa-clock"></i>
                                                    </button>
                                                @endif
                                                @if($task->status !== 'in_progress')
                                                    <button onclick="openStatusModal({{ $task->id }}, 'in_progress')" class="action-btn action-progress" title="Marquer comme en cours">
                                                        <i class="fas fa-spinner"></i>
                                                    </button>
                                                @endif
                                                @if(auth()->user()->isAdmin())
                                                    @if($task->status !== 'completed')
                                                        <button onclick="openStatusModal({{ $task->id }}, 'completed')" class="action-btn action-complete" title="Marquer comme terminée">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                                @if($task->status !== 'for_validation')
                                                    <button onclick="openStatusModal({{ $task->id }}, 'for_validation')" class="action-btn action-status" title="Envoyer pour validation">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </button>
                                                @endif
                                                @if(auth()->user()->isAdmin())
                                                    <button onclick="openDeleteModal({{ $task->id }})" class="action-btn action-delete" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="mobile-cards">
                    @foreach($tasks as $task)
                        <div class="task-card task-card-{{ $task->status }}" data-status="{{ $task->status }}">
                            <div class="task-card-header">
                                <h3 class="task-card-title">{{ $task->title }}</h3>
                                <span class="status-badge status-{{ $task->status }}">
                                    <i class="status-icon fas 
                                        @switch($task->status)
                                            @case('pending') fa-clock @break
                                            @case('in_progress') fa-spinner @break
                                            @case('completed') fa-check-circle @break
                                            @case('for_validation') fa-clipboard-check @break
                                        @endswitch
                                    "></i>
                                    @switch($task->status)
                                        @case('pending') En attente @break
                                        @case('in_progress') En cours @break
                                        @case('completed') Terminée @break
                                        @case('for_validation') En validation @break
                                    @endswitch
                                </span>
                            </div>
                            
                            <div class="task-card-body">
                                <p class="task-card-description">{{ Str::limit($task->description, 120) }}</p>
                                
                                <div class="task-card-info">
                                    <div class="info-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Début: {{ $task->start_date ? $task->start_date->format('d/m/Y') : 'Non définie' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-calendar-check"></i>
                                        <span>Échéance: {{ $task->end_date ? $task->end_date->format('d/m/Y') : 'Non définie' }}</span>
                                    </div>
                                    @if(auth()->user()->isAdmin())
                                        <div class="info-item">
                                            <i class="fas fa-user"></i>
                                            <span>{{ $task->assignedUser->full_name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="task-card-actions">
                                <button onclick="openViewModal({{ $task->id }})" class="action-btn action-view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="openEditModal({{ $task->id }})" class="action-btn action-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($task->status !== 'pending')
                                    <button onclick="openStatusModal({{ $task->id }}, 'pending')" class="action-btn action-pending" title="Marquer comme en attente">
                                        <i class="fas fa-clock"></i>
                                    </button>
                                @endif
                                @if($task->status !== 'in_progress')
                                    <button onclick="openStatusModal({{ $task->id }}, 'in_progress')" class="action-btn action-progress" title="Marquer comme en cours">
                                        <i class="fas fa-spinner"></i>
                                    </button>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    @if($task->status !== 'completed')
                                        <button onclick="openStatusModal({{ $task->id }}, 'completed')" class="action-btn action-complete" title="Marquer comme terminée">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
                                @endif
                                @if($task->status !== 'for_validation')
                                    <button onclick="openStatusModal({{ $task->id }}, 'for_validation')" class="action-btn action-status" title="Envoyer pour validation">
                                        <i class="fas fa-clipboard-check"></i>
                                    </button>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <button onclick="openDeleteModal({{ $task->id }})" class="action-btn action-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    @include('partials.task-modals')

    <!-- Task Data for JavaScript -->
    <script>
        window.tasksData = {!! json_encode($tasks->map(function($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'start_date' => $task->start_date ? $task->start_date->format('Y-m-d') : null,
                'end_date' => $task->end_date ? $task->end_date->format('Y-m-d') : null,
                'assigned_user' => $task->assignedUser ? $task->assignedUser->full_name : null,
                'assigned_to' => $task->assigned_to,
                'created_at' => $task->created_at->format('d/m/Y H:i'),
                'updated_at' => $task->updated_at->format('d/m/Y H:i'),
            ];
        })) !!};

        window.usersData = {!! json_encode($interns->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->full_name,
            ];
        })) !!};

        window.isAdmin = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
        window.csrfToken = '{{ csrf_token() }}';
    </script>

    <script>
        // Add smooth animations on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>

    <!-- Include Modal JavaScript -->
    <script src="{{ asset('js/task-modals.js') }}"></script>
</x-app-layout>
