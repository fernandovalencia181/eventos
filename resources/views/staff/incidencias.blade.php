@extends('staff.layout')

@section('title', 'Incidencias')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 dark:text-white">Incidencias</h1>
            <p class="text-secondary-600 dark:text-secondary-400 mt-2">Reportar y gestionar problemas de eventos</p>
        </div>
        <button onclick="document.getElementById('modal_incidencia').classList.remove('hidden')" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition shadow-md">
            + Reportar Incidencia
        </button>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-4 border border-secondary-200 dark:border-primary-800">
            <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-1">Abiertas</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $abiertas ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-4 border border-secondary-200 dark:border-primary-800">
            <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-1">En progreso</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $enProgreso ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-4 border border-secondary-200 dark:border-primary-800">
            <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-1">Resueltas Hoy</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $resueltasHoy ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-4 border border-secondary-200 dark:border-primary-800">
            <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-1">Total Mes</p>
            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $totalMes ?? 0 }}</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-4 border border-secondary-200 dark:border-primary-800 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                <option value="">Estado: Todos</option>
                <option value="abierta">Abierta</option>
                <option value="en_progreso">En progreso</option>
                <option value="resuelta">Resuelta</option>
            </select>
            <select class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                <option value="">Prioridad: Todas</option>
                <option value="alta">Alta</option>
                <option value="media">Media</option>
                <option value="baja">Baja</option>
            </select>
            <select class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                <option value="">Evento: Todos</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Lista de incidencias -->
    <div class="space-y-4">
        @forelse($incidencias ?? [] as $incidencia)
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-bold text-secondary-900 dark:text-white">{{ $incidencia->titulo ?? 'Incidencia sin título' }}</h3>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ ($incidencia->prioridad ?? 'media') === 'alta' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 
                               (($incidencia->prioridad ?? 'media') === 'media' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                               'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400') }}">
                            {{ ucfirst($incidencia->prioridad ?? 'media') }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ ($incidencia->estado ?? 'abierta') === 'resuelta' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                               (($incidencia->estado ?? 'abierta') === 'en_progreso' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                               'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                            {{ ucfirst(str_replace('_', ' ', $incidencia->estado ?? 'abierta')) }}
                        </span>
                    </div>
                    <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-3">{{ $incidencia->descripcion ?? 'Sin descripción' }}</p>
                    <div class="flex items-center gap-6 text-sm text-secondary-500 dark:text-secondary-400">
                        <span>📅 {{ $incidencia->created_at ? $incidencia->created_at->format('d/m/Y H:i') : 'Fecha desconocida' }}</span>
                        <span>👤 {{ $incidencia->staff->nombre ?? 'Staff' }}</span>
                        <span>🎪 {{ $incidencia->evento->nombre ?? 'Evento' }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm font-medium">Ver detalles</button>
                    @if(($incidencia->estado ?? 'abierta') !== 'resuelta')
                    <button class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 text-sm font-medium">Resolver</button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-12 text-center">
            <div class="text-6xl mb-4">✅</div>
            <p class="text-secondary-600 dark:text-secondary-400">No hay incidencias registradas</p>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal Nueva Incidencia -->
<div id="modal_incidencia" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-primary-900 rounded-lg max-w-lg w-full p-6">
        <h3 class="text-xl font-bold text-secondary-900 dark:text-white mb-4">Reportar Incidencia</h3>
        <form>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Título</label>
                    <input type="text" class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Descripción</label>
                    <textarea rows="4" class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Prioridad</label>
                        <select class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                            <option value="baja">Baja</option>
                            <option value="media" selected>Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Evento</label>
                        <select class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                            <option value="">Seleccionar</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('modal_incidencia').classList.add('hidden')" class="flex-1 bg-secondary-300 dark:bg-primary-800 text-secondary-900 dark:text-white py-2 rounded-lg hover:bg-secondary-400 dark:hover:bg-primary-700 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
                        Reportar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
