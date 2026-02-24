<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="relative w-16 h-16 bg-gradient-to-br from-red-500 via-rose-500 to-pink-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:-rotate-3 transition-all duration-300">
                <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                <div class="relative z-10 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">Gestión de Incidencias</h1>
                <p class="text-secondary-600 dark:text-secondary-400 mt-2">Reportar y gestionar problemas durante los eventos</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <button onclick="document.getElementById('modal_incidencia').classList.remove('hidden')" 
                    class="w-full md:w-auto justify-center bg-gradient-to-r from-red-600 to-rose-600 text-white px-6 py-3 rounded-xl hover:from-red-700 hover:to-rose-700 transition shadow-lg transform hover:scale-105 inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                Reportar Incidencia
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

    @if(session('success'))
    <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Pendientes</h3>
                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-rose-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-secondary-900 dark:text-white">{{ $stats['pendientes'] }}</p>
            <p class="text-sm text-red-600 dark:text-red-400 mt-2">Requieren atención</p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">En Proceso</h3>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-secondary-900 dark:text-white">{{ $stats['en_proceso'] }}</p>
            <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2">Siendo atendidas</p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Resueltas</h3>
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-secondary-900 dark:text-white">{{ $stats['resueltas'] }}</p>
            <p class="text-sm text-green-600 dark:text-green-400 mt-2">Completadas</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-4 border border-secondary-200 dark:border-primary-800 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select id="filtro_estado" class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                <option value="">Estado: Todos</option>
                <option value="pendiente">Pendiente</option>
                <option value="en_proceso">En proceso</option>
                <option value="resuelta">Resuelta</option>
            </select>
            <select id="filtro_prioridad" class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                <option value="">Prioridad: Todas</option>
                <option value="critica">Crítica</option>
                <option value="alta">Alta</option>
                <option value="media">Media</option>
                <option value="baja">Baja</option>
            </select>
            <select id="filtro_evento" class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                <option value="">Evento: Todos</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Lista de incidencias -->
    <div class="space-y-4" id="lista-incidencias">
        @forelse($incidencias as $incidencia)
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6" data-estado="{{ $incidencia->estado }}" data-prioridad="{{ $incidencia->prioridad }}" data-evento="{{ $incidencia->evento_id }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-2xl">
                            @if($incidencia->tipo === 'qr_perdido')
                                📱
                            @elseif($incidencia->tipo === 'error_datos')
                                ❌
                            @elseif($incidencia->tipo === 'acceso_denegado')
                                🚫
                            @elseif($incidencia->tipo === 'tecnico')
                                🔧
                            @else
                                ⚠️
                            @endif
                        </span>
                        <h3 class="text-lg font-bold text-secondary-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $incidencia->tipo)) }}</h3>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $incidencia->prioridad === 'critica' ? 'bg-black text-white' :
                               ($incidencia->prioridad === 'alta' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 
                               ($incidencia->prioridad === 'media' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                               'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400')) }}">
                            {{ ucfirst($incidencia->prioridad) }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $incidencia->estado === 'resuelta' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                               ($incidencia->estado === 'en_proceso' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                               'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                            {{ ucfirst(str_replace('_', ' ', $incidencia->estado)) }}
                        </span>
                    </div>
                    <p class="text-sm text-secondary-900 dark:text-white mb-4">{{ $incidencia->descripcion }}</p>
                    <div class="flex items-center gap-6 text-sm text-secondary-500 dark:text-secondary-400">
                        <span class="inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            {{ $incidencia->created_at->format('d/m/Y H:i') }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            {{ $incidencia->staff->nombre ?? 'Staff' }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                            </svg>
                            {{ $incidencia->evento->nombre }}
                        </span>
                        @if($incidencia->fecha_resolucion)
                        <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Resuelta: {{ $incidencia->fecha_resolucion->format('d/m/Y H:i') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-12 text-center">
            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full flex items-center justify-center shadow-lg mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-xl font-bold text-secondary-900 dark:text-white mb-2">¡Excelente!</p>
            <p class="text-secondary-600 dark:text-secondary-400">No hay incidencias registradas</p>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal Nueva Incidencia -->
<div id="modal_incidencia" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-secondary-900/75 backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-primary-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-secondary-200 dark:border-primary-800">
            
            <!-- Header con gradiente -->
            <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4">
                <div class="flex items-center justify-between text-white">
                    <h3 class="text-xl font-bold flex items-center gap-2" id="modal-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        Reportar Incidencia
                    </h3>
                    <button type="button" onclick="document.getElementById('modal_incidencia').classList.add('hidden')" class="text-white/80 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('staff.registrar-incidencia') }}" class="p-6">
                @csrf
                <div class="space-y-5">
                    <!-- Evento (Automático si staff tiene evento, sino Select) -->
                    @if(optional(auth()->user())->evento_id)
                        <input type="hidden" name="evento_id" value="{{ auth()->user()->evento_id }}">
                        <div class="bg-secondary-50 dark:bg-primary-800/50 p-3 rounded-lg border border-secondary-200 dark:border-primary-700 flex items-center justify-between">
                            <span class="text-sm font-medium text-secondary-500 dark:text-secondary-400">Evento Actual:</span>
                            <span class="text-sm font-bold text-secondary-900 dark:text-white">
                                {{ optional(auth()->user()->evento)->nombre ?? 'Evento Asignado' }}
                            </span>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">Evento *</label>
                            <select name="evento_id" required class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-600 rounded-xl px-4 py-2.5 text-secondary-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                                <option value="">Seleccionar evento...</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">Tipo de Incidencia *</label>
                        <select name="tipo" required class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-600 rounded-xl px-4 py-2.5 text-secondary-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                            <option value="">Seleccione el tipo...</option>
                            <option value="qr_perdido">📱 QR Perdido / No legible</option>
                            <option value="error_datos">❌ Error en Datos / ID Inválido</option>
                            <option value="acceso_denegado">🚫 Acceso Denegado (Múltiples intentos)</option>
                            <option value="tecnico">🔧 Problema Técnico (Scanner/App)</option>
                            <option value="seguridad">👮 Seguridad / Comportamiento</option>
                            <option value="otro">⚠️ Otro motivo</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">Descripción detallada *</label>
                        <textarea name="descripcion" required rows="4" placeholder="Describe qué sucedió, número de ticket afectado, etc..." class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-600 rounded-xl px-4 py-3 text-secondary-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition resize-none"></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('modal_incidencia').classList.add('hidden')" class="flex-1 px-4 py-2.5 bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-600 text-secondary-700 dark:text-secondary-300 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 font-bold transition shadow-sm">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl hover:from-red-700 hover:to-rose-700 font-bold transition shadow-lg transform active:scale-95">
                            Reportar Problema
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Filtros en tiempo real
    const filtroEstado = document.getElementById('filtro_estado');
    const filtroPrioridad = document.getElementById('filtro_prioridad');
    const filtroEvento = document.getElementById('filtro_evento');
    
    function aplicarFiltros() {
        const estado = filtroEstado.value;
        const prioridad = filtroPrioridad.value;
        const evento = filtroEvento.value;
        
        const incidencias = document.querySelectorAll('#lista-incidencias > div');
        
        incidencias.forEach(inc => {
            const incEstado = inc.getAttribute('data-estado');
            const incPrioridad = inc.getAttribute('data-prioridad');
            const incEvento = inc.getAttribute('data-evento');
            
            let mostrar = true;
            
            if (estado && incEstado !== estado) mostrar = false;
            if (prioridad && incPrioridad !== prioridad) mostrar = false;
            if (evento && incEvento !== evento) mostrar = false;
            
            inc.style.display = mostrar ? '' : 'none';
        });
    }
    
    filtroEstado.addEventListener('change', aplicarFiltros);
    filtroPrioridad.addEventListener('change', aplicarFiltros);
    filtroEvento.addEventListener('change', aplicarFiltros);
</script>
@endpush
</x-app-layout>