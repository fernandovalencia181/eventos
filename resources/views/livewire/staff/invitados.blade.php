<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Header con Estilo Unificado -->
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="relative w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-105 transition-all duration-300 flex-shrink-0">
                <div class="absolute inset-0 bg-white/20 rounded-2xl backdrop-blur-sm"></div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-white relative z-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Gestión de Invitados</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-0.5">Ponentes, Personal y Entradas Genéricas</p>
            </div>
        </div>

        <button wire:click="openModal" 
                class="w-full md:w-auto justify-center bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition shadow-md inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Agregar Invitado / Entrada
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex justify-between items-center">
        <span>{{ session('success') }}</span>
        <button wire:click="$set('showModal', false)" class="text-green-700 dark:text-green-400 hover:text-green-900">&times;</button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar Invitado</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nombre o email..." 
                        class="w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 pl-10 h-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            @if(!auth()->user()->evento_id)
            <div class="w-full md:w-64">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtrar por Evento</label>
                <select wire:model.live="evento_id_filter" class="w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 h-10">
                    <option value="">Todos los eventos</option>
                    @foreach($eventos as $evento)
                        <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-primary-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invitado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Detalles</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-primary-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($invitados as $invitado)
                    <tr class="hover:bg-gray-50 dark:hover:bg-primary-800/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-primary-400 to-blue-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($invitado->nombre, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $invitado->nombre }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $invitado->evento->nombre ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $invitado->email ?? '-' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $invitado->telefono ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invitado->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500' }}">
                                {{ ucfirst($invitado->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="descargarEntrada({{ $invitado->id }})" title="Descargar Entrada" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron invitados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-secondary-200 dark:border-primary-800">
            {{ $invitados->links() }}
        </div>
    </div>

    <!-- Estadísticas (MOVIDO AL FINAL) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Total Listado</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600 dark:text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Pendientes</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600 dark:text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['pendientes'] }}</p>
        </div>
    </div>

    <!-- Modal Formulario -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-primary-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white dark:bg-primary-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Agregar Invitado
                            </h3>
                            <div class="mt-4 space-y-4">
                                <!-- Select Evento (Only if multiple available and not locked) -->
                                @if(!auth()->user()->evento_id)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Evento</label>
                                    <select wire:model="evento_id_filter" class="mt-1 block w-full rounded-md border-gray-300 dark:border-primary-600 dark:bg-primary-800 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Seleccione un evento</option>
                                        @foreach($eventos as $evento)
                                            <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('evento_id_filter') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Completo *</label>
                                    <input type="text" wire:model="nombre" class="mt-1 block w-full rounded-md border-gray-300 dark:border-primary-600 dark:bg-primary-800 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                        <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-primary-600 dark:bg-primary-800 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                                        <input type="text" wire:model="telefono" class="mt-1 block w-full rounded-md border-gray-300 dark:border-primary-600 dark:bg-primary-800 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="generar_entrada" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                        Generar Entrada (QR) automáticamente
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">Si se marca, se creará una entrada válida para escanear en puerta.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-primary-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="store" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar
                    </button>
                    <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-primary-600 shadow-sm px-4 py-2 bg-white dark:bg-primary-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
