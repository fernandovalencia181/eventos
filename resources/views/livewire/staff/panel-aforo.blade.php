<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Control de Aforo
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                {{ $evento->nombre }}
            </p>
        </div>
        <button 
            wire:click="actualizarMetricas"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
            🔄 Actualizar
        </button>
    </div>

    <!-- Métricas Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Inscritos -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Inscritos</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $totalInscritos }}
                    </p>
                </div>
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <span class="text-3xl">📋</span>
                </div>
            </div>
        </div>

        <!-- Asistentes Confirmados -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Asistentes</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">
                        {{ $totalAsistentes + $invitadosEspeciales }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        +{{ $invitadosEspeciales }} invitados
                    </p>
                </div>
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <span class="text-3xl">✅</span>
                </div>
            </div>
        </div>

        <!-- Porcentaje de Ocupación -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Ocupación</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">
                        {{ $porcentajeOcupacion }}%
                    </p>
                    <div class="mt-3 bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div 
                            class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full transition-all duration-500"
                            x-data="{}" x-bind:style="'width: ' + {{ min($porcentajeOcupacion, 100) }} + '%'"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aforo Disponible -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Disponible</p>
                    <p class="text-3xl font-bold @if($aforoDisponible > 0) text-blue-600 dark:text-blue-400 @else text-red-600 dark:text-red-400 @endif mt-2">
                        {{ $aforoDisponible }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        de {{ $evento->aforo_maximo }}
                    </p>
                </div>
                <div class="w-16 h-16 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                    <span class="text-3xl">🪑</span>
                </div>
            </div>
        </div>
    </div>

    <!-- No Shows -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            📊 Estadísticas de Asistencia
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tasa de Asistencia</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $totalInscritos > 0 ? round(($totalAsistentes / $totalInscritos) * 100, 1) : 0 }}%
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">No-Shows</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                    {{ $noShows }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Invitados Especiales</p>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                    {{ $invitadosEspeciales }}
                </p>
            </div>
        </div>
    </div>

    <!-- Gráfica de Picos de Llegada -->
    @if(count($picosLlegada) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                📈 Picos de Llegada por Hora
            </h3>
            <div class="space-y-3">
                @php
                    $maxCantidad = collect($picosLlegada)->max('cantidad') ?: 1;
                @endphp
                @foreach($picosLlegada as $pico)
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-16">
                            {{ $pico['hora'] }}
                        </span>
                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-8 relative">
                            <div 
                                class="bg-gradient-to-r from-blue-500 to-cyan-500 h-8 rounded-full flex items-center justify-end px-3 transition-all duration-500"
                                x-data="{}" x-bind:style="'width: ' + {{ ($pico['cantidad'] / $maxCantidad) * 100 }} + '%'"
                            >
                                <span class="text-white font-bold text-sm">{{ $pico['cantidad'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Últimas Entradas -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                🕐 Últimas Entradas Registradas
            </h3>
        </div>
        
        @if($ultimasEntradas->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Usuario</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hora</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Validado por</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($ultimasEntradas as $asistencia)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">
                                            {{ substr($asistencia->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $asistencia->user->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $asistencia->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    {{ $asistencia->hora_entrada->format('H:i:s') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $asistencia->validador->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                No hay entradas registradas aún
            </p>
        @endif
    </div>

    <!-- Botón Exportar -->
    <div class="text-center">
        <button 
            wire:click="exportarAsistentes"
            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium inline-flex items-center gap-2"
        >
            <span>📥</span>
            Exportar Listado de Asistentes (CSV)
        </button>
    </div>
</div>
