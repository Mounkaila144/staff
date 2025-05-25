<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $task->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Détails de la tâche</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Description</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $task->description }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Statut</label>
                                    <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($task->status == 'pending')
                                            bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @elseif($task->status == 'in_progress')
                                            bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($task->status == 'completed')
                                            bg-green-100 text-green-800 border border-green-200
                                        @elseif($task->status == 'for_validation')
                                            bg-purple-100 text-purple-800 border border-purple-200
                                        @endif">
                                        @if($task->status == 'pending')
                                            <i class="fas fa-clock mr-2"></i> En attente
                                        @elseif($task->status == 'in_progress')
                                            <i class="fas fa-spinner mr-2"></i> En cours
                                        @elseif($task->status == 'completed')
                                            <i class="fas fa-check-circle mr-2"></i> Terminée
                                        @elseif($task->status == 'for_validation')
                                            <i class="fas fa-clipboard-check mr-2"></i> Envoyée pour validation
                                        @endif
                                    </span>
                                </div>

                                @if(!auth()->user()->isAdmin() && $task->assigned_to == auth()->id())
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Changer le statut</label>
                                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="flex flex-col md:flex-row gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                            @if(auth()->user()->isAdmin())
                                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Terminée</option>
                                            @endif
                                            <option value="for_validation" {{ $task->status == 'for_validation' ? 'selected' : '' }}>Envoyer pour validation</option>
                                        </select>
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Mettre à jour
                                        </button>
                                    </form>
                                </div>
                                @endif

                                <div class="mt-4">
                                    <h3 class="text-lg font-medium text-gray-900">Dates</h3>
                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600">Date de début</p>
                                            <p class="mt-1">{{ $task->start_date->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Date de fin</p>
                                            <p class="mt-1">{{ $task->end_date->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Assigné à</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $task->user->full_name }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Historique</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Créée le</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Dernière modification</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $task->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i> Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 