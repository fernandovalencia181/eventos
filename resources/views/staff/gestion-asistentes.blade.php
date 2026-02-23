<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Header con Estilo Unificado -->
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="relative w-16 h-16 shrink-0 bg-gradient-to-br from-purple-500 via-indigo-500 to-indigo-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:-rotate-3 transition-all duration-300">
                <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                <!-- Icono SVG Grande -->
                <div class="relative z-10 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Gestión de Asistentes</h1>
                <p class="text-secondary-600 dark:text-secondary-400 mt-2">Administración total de usuarios</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <button wire:click="openModal" 
                class="w-full md:w-auto justify-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-lg transform hover:scale-105 inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Agregar Manualmente
            </button>

            <a href="{{ route('staff.index') }}" 
            class="w-full sm:w-auto justify-center bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 border border-secondary-300 dark:border-primary-600 px-5 py-2.5 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 transition shadow-sm inline-flex items-center gap-2 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Volver al Panel
            </a>
        </div>
    </div>

    <!-- Banner de Sincronización (Para registros antiguos) -->
    <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 justify-between">
            <div class="flex gap-3">
                <div class="bg-yellow-100 dark:bg-yellow-900/40 p-2 rounded-full shrink-0">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="text-sm text-yellow-800 dark:text-yellow-300">
                    <p class="font-bold">¿Faltan usuarios?</p>
                    <p class="text-yellow-700 dark:text-yellow-400 text-xs sm:text-sm">Si no ves algunos asistentes antiguos, actualiza la lista aquí.</p>
                </div>
            </div>
            <button wire:click="syncLegacyData" wire:loading.attr="disabled" class="w-full sm:w-auto h-11 justify-center whitespace-nowrap bg-yellow-100 text-yellow-700 dark:bg-yellow-800 dark:text-yellow-100 px-4 rounded-lg text-sm font-bold hover:bg-yellow-200 dark:hover:bg-yellow-700 transition flex items-center gap-2 relative overflow-hidden">
                <!-- Normal State -->
                <span wire:loading.remove wire:target="syncLegacyData" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Sincronizar Ahora
                </span>

                <!-- Loading State -->
                <span wire:loading wire:target="syncLegacyData" class="flex items-center gap-2">
                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sincronizando...
                </span>
            </button>
        </div>
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
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar Asistente</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, email o telófono..." 
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

    <!-- Tabla (Vista Escritorio) -->
    <div class="hidden md:block bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-primary-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Asistente</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ciclo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo / Correo / Telófono</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado Entrada</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Entrada / QR</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-primary-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($invitados as $invitado)
                    <tr class="hover:bg-gray-50 dark:hover:bg-primary-800/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-primary-400 to-blue-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($invitado->nombre_asistente ?? '-', 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $invitado->nombre_asistente }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $invitado->evento->nombre ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @php
                                $ciclo = 'N/A';
                                if ($invitado->user) {
                                    $reg = $invitado->user->registrations->where('event_id', $invitado->evento_id)->first();
                                    $ciclo = $reg->course ?? 'N/A';
                                } elseif ($invitado->guest && $invitado->guest->registration) {
                                    $ciclo = $invitado->guest->registration->course ?? 'N/A';
                                }
                            @endphp
                            {{ Str::limit($ciclo, 20) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <!-- Etiqueta de Tipo -->
                                <div>
                                    @if($invitado->user_id)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            Titular Registrado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                            Invitado / Acompaóante
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Email si existe (Solo usuarios registrados) -->
                                @if($invitado->user)
                                <div class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $invitado->user->email }}
                                </div>
                                @endif

                                @php
                                    // Bósqueda inteligente de telófono:
                                    // 1. Si es Usuario Registrado: user->phone
                                    // 2. Si es Guest (antiguo o manual migrado): guest->phone
                                    $telefono = null;
                                    if ($invitado->user) {
                                        $telefono = $invitado->user->phone;
                                    } elseif ($invitado->guest) {
                                        $telefono = $invitado->guest->phone;
                                    }
                                @endphp

                                @if($telefono)
                                    @php
                                        // Limpiar telófono para formato wa.me
                                        $telefonoClean = preg_replace('/[^0-9]/', '', $telefono);
                                        $mensaje = "Hola " . ($invitado->nombre_asistente ?? 'Asistente') . ", aquó tienes tu entrada para el evento " . ($invitado->evento->nombre ?? '') . ".";
                                    @endphp
                                    <div class="text-sm flex items-center gap-2 mt-1">
                                        <a href="https://wa.me/{{ $telefonoClean }}?text={{ urlencode($mensaje) }}" 
                                           target="_blank" 
                                           title="Enviar entrada por WhatsApp"
                                           class="mt-1 flex items-center text-sm text-green-600 dark:text-green-400 font-medium hover:text-green-700 transition-colors">
                                            
                                            <!-- Icono WhatsApp -->
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            
                                            {{ $telefono }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invitado->estado === 'generada' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                {{ ucfirst($invitado->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-3">
                                <button type="button" wire:click="verQr('{{ $invitado->id }}')" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition" title="Ver QR">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                                    </svg>
                                </button>
                                <button type="button" wire:click="descargarEntrada('{{ $invitado->id }}')" title="Descargar PDF" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                                    </svg>
                                </button>
                            </div>
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

    <!-- VISTA CAMBIADA A MóVIL (Tarjetas) -->
    <div class="md:hidden space-y-4 mb-8">
        @forelse($invitados as $invitado)
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-5 border border-secondary-200 dark:border-primary-800">
            
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-primary-400 to-blue-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                        {{ substr($invitado->nombre_asistente ?? '-', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white leading-tight">{{ $invitado->nombre_asistente }}</h3>
                        <p class="text-xs text-secondary-500 dark:text-secondary-400">{{ $invitado->evento->nombre ?? 'N/A' }}</p>
                    </div>
                </div>
                <!-- Estado Entrada -->
                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $invitado->estado === 'generada' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                    {{ ucfirst($invitado->estado) }}
                </span>
            </div>

            <!-- Datos Secundarios -->
            <div class="space-y-2 mb-4">
                <!-- Ciclo -->
                <div class="bg-gray-50 dark:bg-primary-800/50 p-2.5 rounded-lg border border-gray-100 dark:border-primary-700">
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Ciclo</span>
                    @php
                        $ciclo = 'N/A';
                        if ($invitado->user) {
                            $reg = $invitado->user->registrations->where('event_id', $invitado->evento_id)->first();
                            $ciclo = $reg->course ?? 'N/A';
                        } elseif ($invitado->guest && $invitado->guest->registration) {
                            $ciclo = $invitado->guest->registration->course ?? 'N/A';
                        }
                    @endphp
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($ciclo, 30) }}</span>
                </div>

                <!-- Contacto & Telófono -->
                <div class="flex flex-col gap-1">
                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <span>
                            @if($invitado->user_id) <span class="text-blue-600 dark:text-blue-400 font-bold">Titular</span>
                            @else <span class="text-purple-600 dark:text-purple-400 font-bold">Invitado</span> @endif
                        </span>
                        @if($invitado->user)
                            <span>{{ $invitado->user->email }}</span>
                        @endif
                    </div>
                </div>

                <!-- WhatsApp Button -->
                @php
                    $telefono = null;
                    if ($invitado->user) {
                        $telefono = $invitado->user->phone;
                    } elseif ($invitado->guest) {
                        $telefono = $invitado->guest->phone;
                    }
                @endphp
                @if($telefono)
                    @php
                        $telefonoClean = preg_replace('/[^0-9]/', '', $telefono);
                        $mensaje = "Hola " . ($invitado->nombre_asistente ?? 'Asistente') . ", aquó tienes tu entrada para el evento " . ($invitado->evento->nombre ?? '') . ".";
                    @endphp
                    <a href="https://wa.me/{{ $telefonoClean }}?text={{ urlencode($mensaje) }}" 
                       target="_blank" 
                       class="flex items-center justify-center w-full py-2 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-lg text-sm font-semibold text-green-700 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/40 transition gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        {{ $telefono }}
                    </a>
                @endif
            </div>

            <!-- Botones de Acción -->
            <div class="flex gap-3 pt-3 border-t border-secondary-100 dark:border-primary-800">
                <button type="button" wire:click="verQr('{{ $invitado->id }}')" class="flex-1 py-2 bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900/60 transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                    </svg>
                    Ver QR
                </button>
                <button type="button" wire:click="descargarEntrada('{{ $invitado->id }}')" class="flex-1 py-2 bg-purple-50 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 rounded-lg text-sm font-medium hover:bg-purple-100 dark:hover:bg-purple-900/60 transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    PDF
                </button>
            </div>
        </div>
        @empty
        <div class="text-center p-8 bg-white dark:bg-primary-900 rounded-lg border border-dashed border-secondary-300 dark:border-primary-700">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="text-secondary-500 dark:text-secondary-400 font-medium">No se encontraron asistentes.</p>
        </div>
        @endforelse

        <div class="mt-4">
            {{ $invitados->links() }}
        </div>
    </div>

    <!-- QR Modal -->
    @if($viewingQr)
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity" 
         @if($showDynamicQr) wire:poll.1s="refreshDynamicQr" @endif>
        
        <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden transform transition-all scale-100 animate-fade-in-up">
            
            <!-- Header Mejorado -->
            <div class="flex justify-between items-center p-4 border-b border-gray-100 dark:border-primary-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ $showDynamicQr ? 'Acceso Dinámico' : 'Acceso Estático' }}
                </h3>
                <button wire:click="closeQr" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition bg-gray-100 dark:bg-primary-800 p-2 rounded-full hover:bg-gray-200 dark:hover:bg-primary-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 text-center">
                <!-- TABS -->
                <div class="flex justify-center space-x-1 mb-6 bg-gray-100 dark:bg-primary-800 p-1 rounded-xl">
                    <button wire:click="setQrMode(false)" class="flex-1 px-4 py-2 text-sm font-bold rounded-lg transition-all {{ !$showDynamicQr ? 'bg-white dark:bg-primary-600 shadow-sm text-primary-700 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        Estático
                    </button>
                    @if($canShowDynamicQr)
                    <button wire:click="setQrMode(true)" class="flex-1 px-4 py-2 text-sm font-bold rounded-lg transition-all {{ $showDynamicQr ? 'bg-white dark:bg-primary-600 shadow-sm text-primary-700 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        Dinámico
                    </button>
                    @endif
                </div>

                <p class="text-sm font-medium text-gray-900 dark:text-white truncate mb-1">{{ $currentGuestName }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">Muestra este código al staff</p>
                
                <div class="bg-white p-4 rounded-xl border-2 border-dashed border-gray-200 inline-block mb-6 relative group mx-auto">
                    <img src="{{ $currentQr }}" alt="QR Code" class="h-48 w-48 object-contain transition-opacity duration-300">
                     
                     <!-- Dynamic Timer Badge -->
                     @if($showDynamicQr)
                        <div class="absolute bottom-2 right-2 bg-indigo-100 text-indigo-800 text-xs font-bold px-2 py-0.5 rounded-full border border-indigo-200 shadow-sm flex items-center gap-1">
                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ $timeLeft }}s
                        </div>
                     @endif
                </div>
                
                <!-- Progress Bar -->
                @if($showDynamicQr)
                    <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700 max-w-[220px] mx-auto mb-6 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-1000 ease-linear" 
                                style="width: {{ ($timeLeft / 20) * 100 }}%"></div>
                    </div>
                @endif
                
                <div class="bg-gray-50 dark:bg-primary-800/50 rounded-lg p-3 text-xs text-gray-400 dark:text-gray-500 font-mono break-all border border-gray-100 dark:border-primary-800">
                    ID: {{ $currentTicketId }}
                </div>
            </div>
            
            <!-- Footer Action -->
            <div class="p-4 bg-gray-50 dark:bg-primary-800 border-t border-gray-100 dark:border-primary-700">
                <button wire:click="closeQr" class="w-full bg-white dark:bg-primary-700 border border-gray-300 dark:border-primary-600 text-gray-700 dark:text-gray-200 py-2.5 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-primary-600 transition shadow-sm">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Estadósticas (MOVIDO AL FINAL) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Total Entradas</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600 dark:text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Usuarios Registrados</h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600 dark:text-blue-400">
                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['usuarios'] }}</p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Acompaóantes</h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-purple-500 dark:text-purple-400">
                    <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM15.75 9.75a3 3 0 116 0 3 3 0 01-6 0zM2.25 9.75a3 3 0 116 0 3 3 0 01-6 0zM6.31 15.117A6.745 6.745 0 0112 12a6.745 6.745 0 016.709 7.498.75.75 0 01-.372.568A12.696 12.696 0 0112 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 01-.372-.568 6.787 6.787 0 011.019-4.38z" clip-rule="evenodd" />
                    <path d="M5.082 14.254a8.287 8.287 0 00-1.308 5.135 9.687 9.687 0 01-1.764-.44l-.115-.04a.563.563 0 01-.373-.487l-.01-.121a3.75 3.75 0 013.57-4.047zM20.226 19.389a8.287 8.287 0 00-1.308-5.135 3.75 3.75 0 013.57 4.047l-.01.121a.563.563 0 01-.373.486l-.115.04c-.567.2-1.156.349-1.764.441z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['invitados'] }}</p>
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
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telófono</label>
                                        <input type="text" wire:model="telefono" class="mt-1 block w-full rounded-md border-gray-300 dark:border-primary-600 dark:bg-primary-800 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="generar_entrada" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                        Generar Entrada (QR) automóticamente
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">Si se marca, se crearó una entrada vólida para escanear en puerta.</p>
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
