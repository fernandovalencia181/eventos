@extends('staff.layout')

@section('title', 'Gestión de Incidencias')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 dark:text-white">⚠️ Gestión de Incidencias</h1>
            <p class="text-secondary-600 dark:text-secondary-400 mt-2">Reportar y gestionar problemas durante los eventos</p>
        </div>
        <button onclick="document.getElementById('modal_incidencia').classList.remove('hidden')" 
                class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition shadow-md inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            Reportar Incidencia
        </button>
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
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-600 dark:text-red-400">
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
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-yellow-600 dark:text-yellow-400">
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
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600 dark:text-green-400">
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
            <div class="text-6xl mb-4">✅</div>
            <p class="text-lg font-medium text-secondary-900 dark:text-white mb-2">¡Excelente!</p>
            <p class="text-secondary-600 dark:text-secondary-400">No hay incidencias registradas</p>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal Nueva Incidencia -->
<div id="modal_incidencia" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-primary-900 rounded-lg max-w-lg w-full p-6">
        <h3 class="text-xl font-bold text-secondary-900 dark:text-white mb-6">⚠️ Reportar Incidencia</h3>
        <form method="POST" action="{{ route('staff.registrar-incidencia') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Evento *</label>
                    <select name="evento_id" required class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                        <option value="">Seleccionar evento...</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Tipo de Incidencia *</label>
                    <select name="tipo" required class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                        <option value="qr_perdido">📱 QR Perdido</option>
                        <option value="error_datos">❌ Error en Datos de Matrícula</option>
                        <option value="acceso_denegado">🚫 Acceso Denegado</option>
                        <option value="tecnico">🔧 Problema Técnico</option>
                        <option value="otro">⚠️ Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Descripción *</label>
                    <textarea name="descripcion" required rows="4" placeholder="Describe el problema con detalle..." class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Prioridad *</label>
                    <select name="prioridad" required class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                        <option value="baja">🟢 Baja</option>
                        <option value="media" selected>🟡 Media</option>
                        <option value="alta">🔴 Alta</option>
                        <option value="critica">⚫ Crítica</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('modal_incidencia').classList.add('hidden')" class="flex-1 bg-secondary-300 dark:bg-primary-800 text-secondary-900 dark:text-white py-3 rounded-lg hover:bg-secondary-400 dark:hover:bg-primary-700 transition font-medium">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition font-medium">
                        Reportar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
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
@endsection
    </div>
</div>
@endsection
