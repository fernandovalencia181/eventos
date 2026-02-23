<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Cabecera -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="relative w-16 h-16 bg-gradient-to-br from-cyan-400 via-blue-500 to-blue-700 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                <div class="relative z-10">
                    <svg viewBox="0 0 32 32" class="w-8 h-8" fill="none">
                        <rect x="4" y="4" width="10" height="10" rx="2" fill="white" opacity="0.9"/>
                        <rect x="18" y="4" width="10" height="10" rx="2" fill="white" opacity="0.7"/>
                        <rect x="4" y="18" width="10" height="10" rx="2" fill="white" opacity="0.7"/>
                        <rect x="18" y="18" width="10" height="10" rx="2" fill="white" opacity="0.9"/>
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-600 to-blue-700 bg-clip-text text-transparent">Panel de Control de Staff</h1>
                <p class="text-secondary-600 dark:text-secondary-400 mt-2">Vista general de las operaciones del evento</p>
            </div>
        </div>
    </div>

    <!-- Evento Actual (Top Banner) -->
    <div class="mb-6">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-sm border border-secondary-200 dark:border-primary-800 p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-secondary-900 dark:text-white inline-flex items-center gap-2">
                    <svg viewBox="0 0 20 20" class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="currentColor">
                        <rect x="3" y="4" width="14" height="13" rx="2" fill="currentColor" opacity="0.2"/>
                        <rect x="3" y="2" width="14" height="4" rx="1" fill="currentColor"/>
                        <circle cx="7" cy="9" r="1" fill="currentColor"/>
                        <circle cx="10" cy="9" r="1" fill="currentColor"/>
                        <circle cx="13" cy="9" r="1" fill="currentColor"/>
                    </svg>
                    Evento Actual
                </h3>
                <div class="flex items-center gap-3">
                     @forelse($eventos as $evento)
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-secondary-50 dark:bg-primary-800 rounded-md text-sm">
                            <span class="font-medium text-secondary-900 dark:text-white">{{ $evento->nombre }}</span>
                            <span class="text-secondary-400">|</span>
                            <span class="text-secondary-600 dark:text-secondary-400">{{ $evento->fecha->format('d M Y - H:i') }}</span>
                        </span>
                    @empty
                        <span class="text-sm text-secondary-500 dark:text-secondary-400 italic">No hay eventos activos</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos (MOVIDO ARRIBA) -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- Escáner QR -->
        <a href="{{ route('staff.scanner') }}" class="bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg p-6 hover:from-primary-700 hover:to-primary-800 transition shadow-lg transform hover:scale-105 group relative overflow-hidden">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform duration-300 backdrop-blur-sm">
                    <svg viewBox="0 0 24 24" class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Escáner QR</h3>
                <p class="text-primary-100 text-xs">Validar entradas</p>
            </div>
        </a>

        <!-- Lista de Asistencia -->
        <a href="{{ route('staff.asistencia') }}" class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg p-6 hover:from-green-700 hover:to-green-800 transition shadow-lg transform hover:scale-105 group relative overflow-hidden">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform duration-300 backdrop-blur-sm">
                    <svg viewBox="0 0 24 24" class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Asistencia</h3>
                <p class="text-green-100 text-xs">Ver listado en vivo</p>
            </div>
        </a>

        <!-- Gestión de Invitados -->
        <a href="{{ route('staff.invitados') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg p-6 hover:from-purple-700 hover:to-indigo-700 transition shadow-lg transform hover:scale-105 group relative overflow-hidden">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform duration-300 backdrop-blur-sm">
                    <svg viewBox="0 0 24 24" class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Gestión de Invitados</h3>
                <p class="text-purple-100 text-xs">Gestionar accesos</p>
            </div>
        </a>

        <!-- Incidencias -->
        <a href="{{ route('staff.incidencias') }}" class="bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-lg p-6 hover:from-red-700 hover:to-rose-700 transition shadow-lg transform hover:scale-105 group relative overflow-hidden">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform duration-300 backdrop-blur-sm">
                    <svg viewBox="0 0 24 24" class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Incidencias</h3>
                <p class="text-red-100 text-xs">Reportar problemas</p>
            </div>
        </a>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Aforo Actual -->
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Aforo Actual</h3>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-all">
                    <svg viewBox="0 0 24 24" class="w-6 h-6" fill="white">
                        <circle cx="8" cy="6" r="3"/>
                        <circle cx="16" cy="6" r="2.5" opacity="0.7"/>
                        <path d="M2 18c0-2.8 2.2-5 5-5h2c2.8 0 5 2.2 5 5v1H2v-1z"/>
                        <path d="M14 18c0-2 1.3-3.5 3-3.5h2c1.7 0 3 1.5 3 3.5v1h-8v-1z" opacity="0.7"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['aforo_actual'] }}</p>
            <p class="text-sm text-blue-600 dark:text-blue-400 mt-2 inline-flex items-center gap-1">
                <svg viewBox="0 0 12 12" class="w-3 h-3" fill="currentColor">
                    <circle cx="6" cy="4" r="2"/>
                    <path d="M2 10c0-2 1.8-3.5 4-3.5s4 1.5 4 3.5v1H2v-1z"/>
                </svg>
                Personas dentro
            </p>
        </div>

        <!-- Validados Hoy -->
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Validados</h3>
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-all">
                    <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="white" stroke-width="2"/>
                        <path d="M8 12l2 2 5-5" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['validadas_hoy'] }}</p>
            <p class="text-sm text-green-600 dark:text-green-400 mt-2 inline-flex items-center gap-1">
                <svg viewBox="0 0 12 12" class="w-3 h-3" fill="currentColor">
                    <path d="M6 1l1 3h3l-2.5 2 1 3L6 7 3.5 9l1-3L2 4h3z"/>
                </svg>
                Accesos validados
            </p>
        </div>

        <!-- Meta Asistencia (NUEVO) -->
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Meta Asistencia</h3>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-all">
                    <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            
            @php
                $totalCheckins = $eventos->sum(fn($e) => $e->checkins->count());
                $totalInscritos = $eventos->sum('inscritos');
                $porcentaje = $totalInscritos > 0 ? ($totalCheckins / $totalInscritos) * 100 : 0;
            @endphp

            <div class="flex items-end gap-2 mb-2">
                <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $totalCheckins }}</p>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 mb-1">/ {{ $totalInscritos }}</p>
            </div>
            
            <div class="w-full bg-secondary-200 dark:bg-primary-800 rounded-full h-2.5 mb-1">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2.5 rounded-full" style="width: {{ $porcentaje }}%"></div>
            </div>
            <p class="text-xs text-secondary-500 dark:text-secondary-400 text-right">{{ round($porcentaje) }}% completado</p>
        </div>

        <!-- Incidencias Pendientes -->
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Incidencias</h3>
                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-rose-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-all">
                    <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none">
                        <path d="M12 2L2 20h20L12 2z" fill="white" stroke="white" stroke-width="2"/>
                        <path d="M12 9v4" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="12" cy="16" r="1" fill="#ef4444"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['incidencias'] }}</p>
            <p class="text-sm text-red-600 dark:text-red-400 mt-2 inline-flex items-center gap-1">
                <svg viewBox="0 0 12 12" class="w-3 h-3" fill="currentColor">
                    <circle cx="6" cy="6" r="5"/>
                    <path d="M6 3v3" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="6" cy="8.5" r="0.5" fill="white"/>
                </svg>
                Pendientes
            </p>
        </div>
    </div>

    <!-- Evento Actual y Picos de Llegada -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Picos de Llegada -->
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6 lg:col-span-2">
            <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-4 inline-flex items-center gap-2">
                <svg viewBox="0 0 20 20" class="w-5 h-5" fill="currentColor">
                    <path d="M2 16l3-4 3 3 4-6 4 4 2-3v6H2z" fill="currentColor" opacity="0.3"/>
                    <path d="M2 16l3-4 3 3 4-6 4 4 2-3" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Picos de Llegada (Hoy)
            </h3>
            <div class="space-y-3">
                @forelse($picos_llegada as $pico)
                <div class="flex items-center gap-4">
                    <div class="w-16 text-sm font-medium text-secondary-900 dark:text-white">
                        {{ str_pad($pico->hora, 2, '0', STR_PAD_LEFT) }}:00
                    </div>
                    <div class="flex-1">
                        <div class="h-8 bg-secondary-200 dark:bg-primary-800 rounded-full overflow-hidden">
                            @php
                                $max = $picos_llegada->max('total');
                                $width = $max > 0 ? ($pico->total / $max) * 100 : 0;
                            @endphp
                            <div class="h-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-end px-3" style="width: {{ $width }}%;">
                                <span class="text-xs font-bold text-white">{{ $pico->total }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-secondary-600 dark:text-secondary-400 py-8">No hay datos de hoy</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Últimas Validaciones -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
        <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-4 inline-flex items-center gap-2">
            <svg viewBox="0 0 20 20" class="w-5 h-5" fill="currentColor">
                <circle cx="10" cy="10" r="8" fill="none" stroke="currentColor" stroke-width="2"/>
                <path d="M10 5v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Últimas Validaciones
        </h3>
        
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-secondary-200 dark:border-primary-800">
                        <th class="text-left py-3 px-4 text-sm font-medium text-secondary-600 dark:text-secondary-400">Hora</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-secondary-600 dark:text-secondary-400">Asistente</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-secondary-600 dark:text-secondary-400">Método</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-secondary-600 dark:text-secondary-400">Validado por</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimas_validaciones as $validacion)
                    <tr class="border-b border-secondary-100 dark:border-primary-800 hover:bg-secondary-50 dark:hover:bg-primary-800/50">
                        <td class="py-3 px-4 text-sm text-secondary-900 dark:text-white">
                            {{ $validacion->created_at->format('H:i:s') }}
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-sm font-medium text-secondary-900 dark:text-white">{{ $validacion->ticket->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-secondary-600 dark:text-secondary-400">{{ $validacion->ticket->user->matricula ?? 'N/A' }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 text-xs rounded-full font-medium inline-flex items-center gap-1 {{ $validacion->metodo === 'qr' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                @if($validacion->metodo === 'qr')
                                    <svg viewBox="0 0 12 12" class="w-3 h-3" fill="currentColor">
                                        <rect x="1" y="1" width="4" height="4" rx="0.5"/>
                                        <rect x="7" y="1" width="4" height="4" rx="0.5"/>
                                        <rect x="1" y="7" width="4" height="4" rx="0.5"/>
                                    </svg>
                                    QR
                                @else
                                    <svg viewBox="0 0 12 12" class="w-3 h-3" fill="none">
                                        <path d="M2 6h8M7 3l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                    Manual
                                @endif
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-secondary-600 dark:text-secondary-400">
                            {{ $validacion->staff->nombre ?? 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-secondary-600 dark:text-secondary-400">
                            No hay validaciones recientes
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @forelse($ultimas_validaciones as $validacion)
            <div class="bg-secondary-50 dark:bg-primary-800 p-4 rounded-xl border border-secondary-200 dark:border-primary-700">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-sm font-bold text-secondary-900 dark:text-white">{{ $validacion->ticket->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-secondary-600 dark:text-secondary-400">{{ $validacion->ticket->user->matricula ?? 'N/A' }}</p>
                    </div>
                    <span class="text-xs font-mono text-secondary-500 dark:text-secondary-500">
                        {{ $validacion->created_at->format('H:i:s') }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center mt-3 pt-3 border-t border-secondary-100 dark:border-primary-700">
                    <span class="px-2 py-1 text-xs rounded-lg font-medium inline-flex items-center gap-1 {{ $validacion->metodo === 'qr' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                        @if($validacion->metodo === 'qr')
                            QR
                        @else
                            Manual
                        @endif
                    </span>
                    <div class="text-right">
                        <span class="text-xs text-secondary-500 dark:text-secondary-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $validacion->staff->nombre ?? 'Staff' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
                <div class="text-center py-6 text-secondary-500 dark:text-secondary-400">
                    No hay validaciones recientes
                </div>
            @endforelse
        </div>
    </div>
</div>
</x-app-layout>

