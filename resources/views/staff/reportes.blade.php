@extends('staff.layout')

@section('title', 'Reportes y Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-900 dark:text-white">📊 Reportes y Analytics</h1>
        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Métricas operativas y exportación de datos</p>
    </div>

    <!-- Estadísticas Generales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-blue-100">Total Eventos</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats_generales['total_eventos'] }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-green-100">Total Asistentes</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats_generales['total_asistentes'] }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-purple-100">Promedio Asistencia</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ number_format($stats_generales['promedio_asistencia'], 1) }}%</p>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-red-100">Total Incidencias</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <p class="text-4xl font-bold">{{ $stats_generales['total_incidencias'] }}</p>
        </div>
    </div>

    <!-- Tabla de Eventos con Métricas -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 overflow-hidden mb-8">
        <div class="p-6 border-b border-secondary-200 dark:border-primary-800">
            <h2 class="text-xl font-bold text-secondary-900 dark:text-white">📈 Desempeño por Evento</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-secondary-50 dark:bg-primary-800">
                    <tr>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Evento</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Fecha</th>
                        <th class="text-center py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Inscritos</th>
                        <th class="text-center py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Asistentes</th>
                        <th class="text-center py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">% Asistencia</th>
                        <th class="text-center py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">No-Shows</th>
                        <th class="text-center py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventos as $evento)
                    @php
                        $porcentaje_asistencia = $evento->inscritos > 0 ? ($evento->asistentes / $evento->inscritos) * 100 : 0;
                        $no_shows = $evento->inscritos - $evento->asistentes;
                    @endphp
                    <tr class="border-b border-secondary-100 dark:border-primary-800 hover:bg-secondary-50 dark:hover:bg-primary-800/50">
                        <td class="py-4 px-6">
                            <p class="text-sm font-medium text-secondary-900 dark:text-white">{{ $evento->nombre }}</p>
                            <p class="text-xs text-secondary-600 dark:text-secondary-400">{{ $evento->ubicacion ?? 'Sin ubicación' }}</p>
                        </td>
                        <td class="py-4 px-6 text-sm text-secondary-900 dark:text-white">
                            {{ $evento->fecha->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-3 py-1 text-sm font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">
                                {{ $evento->inscritos }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-3 py-1 text-sm font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                {{ $evento->asistentes }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-sm font-bold {{ $porcentaje_asistencia >= 75 ? 'text-green-600' : ($porcentaje_asistencia >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($porcentaje_asistencia, 1) }}%
                                </span>
                                <div class="w-full bg-secondary-200 dark:bg-primary-800 rounded-full h-2 overflow-hidden">
                                    <div class="h-full {{ $porcentaje_asistencia >= 75 ? 'bg-green-500' : ($porcentaje_asistencia >= 50 ? 'bg-yellow-500' : 'bg-red-500') }} transition-all" style="width: {{ min($porcentaje_asistencia, 100) }}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-3 py-1 text-sm font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded-full">
                                {{ $no_shows }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('staff.exportar', ['evento_id' => $evento->id]) }}" 
                               class="inline-flex items-center gap-1 px-3 py-1 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-xs transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Exportar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="text-6xl mb-4">📊</div>
                            <p class="text-secondary-600 dark:text-secondary-400">No hay eventos para mostrar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Botón de Exportación Global -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-8 text-white shadow-lg text-center">
        <div class="text-5xl mb-4">📥</div>
        <h2 class="text-2xl font-bold mb-2">Exportar Datos Completos</h2>
        <p class="text-green-100 mb-6">Descarga el listado final de asistentes validados para fines académicos o de acreditación</p>
        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('staff.exportar') }}" 
               class="bg-white text-green-700 px-8 py-3 rounded-lg hover:bg-green-50 font-bold transition inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Exportar Todos (.CSV)
            </a>
        </div>
    </div>

</div>
@endsection
