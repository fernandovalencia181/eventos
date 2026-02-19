<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-secondary-900">
                Control de Aforo
            </h1>
            <p class="text-secondary-500 mt-2">
                {{ $evento->nombre }}
            </p>
        </div>
        <button 
            wire:click="actualizarMetricas"
            class="px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Actualizar
        </button>
    </div>

    <!-- Métricas Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Inscritos -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-secondary-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Total Inscritos</p>
                    <p class="text-3xl font-bold text-secondary-900 mt-2">
                        {{ $totalInscritos }}
                    </p>
                </div>
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Asistentes Confirmados -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-secondary-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Asistentes</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ $totalAsistentes + $invitadosEspeciales }}
                    </p>
                    <p class="text-xs text-secondary-500 mt-1">
                        +{{ $invitadosEspeciales }} invitados
                    </p>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Porcentaje de Ocupación -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-secondary-100">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-secondary-500 text-sm font-medium">Ocupación</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">
                        {{ $porcentajeOcupacion }}%
                    </p>
                    <div class="mt-3 bg-secondary-200 rounded-full h-3">
                        <div 
                            class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full transition-all duration-500"
                            x-data="{}" x-bind:style="'width: ' + {{ min($porcentajeOcupacion, 100) }} + '%'"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aforo Disponible -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-secondary-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Disponible</p>
                    <p class="text-3xl font-bold @if($aforoDisponible > 0) text-primary-600 @else text-red-600 @endif mt-2">
                        {{ $aforoDisponible }}
                    </p>
                    <p class="text-xs text-secondary-500 mt-1">
                        de {{ $evento->aforo_maximo }}
                    </p>
                </div>
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                    <span class="text-3xl">🪑</span>
                </div>
            </div>
        </div>
    </div>

    <!-- No Shows -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-secondary-100">
        <h3 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Estadísticas de Asistencia
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-secondary-500">Tasa de Asistencia</p>
                <p class="text-2xl font-bold text-secondary-900">
                    {{ $totalInscritos > 0 ? round(($totalAsistentes / $totalInscritos) * 100, 1) : 0 }}%
                </p>
            </div>
            <div>
                <p class="text-sm text-secondary-500">No-Shows</p>
                <p class="text-2xl font-bold text-red-600">
                    {{ $noShows }}
                </p>
            </div>
            <div>
                <p class="text-sm text-secondary-500">Invitados Especiales</p>
                <p class="text-2xl font-bold text-yellow-600">
                    {{ $invitadosEspeciales }}
                </p>
            </div>
        </div>
    </div>

    <!-- Gráfica de Picos de Llegada -->
    @if(count($picosLlegada) > 0)
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                Picos de Llegada por Hora
            </h3>
            <div class="space-y-3">
                @php
                    $maxCantidad = collect($picosLlegada)->max('cantidad') ?: 1;
                @endphp
                @foreach($picosLlegada as $pico)
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-secondary-600 w-16">
                            {{ $pico['hora'] }}
                        </span>
                        <div class="flex-1 bg-secondary-200 rounded-full h-8 relative">
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
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-secondary-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Últimas Entradas Registradas
            </h3>
        </div>
        
        @if($ultimasEntradas->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-secondary-500 uppercase">Usuario</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-secondary-500 uppercase">Hora</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-secondary-500 uppercase">Validado por</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-200">
                        @foreach($ultimasEntradas as $asistencia)
                            <tr class="hover:bg-secondary-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">
                                            {{ substr($asistencia->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-secondary-900">{{ $asistencia->user->name }}</p>
                                            <p class="text-sm text-secondary-500">{{ $asistencia->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-secondary-900">
                                    {{ $asistencia->hora_entrada->format('H:i:s') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-secondary-600">
                                    {{ $asistencia->validador->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-secondary-500 text-center py-8">
                No hay entradas registradas aún
            </p>
        @endif
    </div>

    <!-- Botón Exportar -->
    <div class="text-center">
        <button 
            wire:click="exportarAsistentes"
            class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 font-medium inline-flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Exportar Listado de Asistentes (CSV)
        </button>
    </div>
</div>
