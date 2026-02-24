<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Cabecera -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="relative w-16 h-16 bg-gradient-to-br from-green-400 via-emerald-500 to-teal-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:-rotate-3 transition-all duration-300">
                <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                <div class="relative z-10 text-white">
                    <svg viewBox="0 0 24 24" class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Lista de Asistencia en Vivo</h1>
                <p class="text-secondary-600 dark:text-secondary-400 mt-2">Búsqueda manual por nombre o email</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <a href="{{ route('staff.exportar', ['evento_id' => $evento_id]) }}" 
            class="w-full md:w-auto justify-center bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl hover:from-green-700 hover:to-emerald-700 transition shadow-lg transform hover:scale-105 inline-flex items-center gap-2">
                <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Exportar CSV
            </a>
            
            <a href="{{ route('staff.index') }}" 
            class="w-full sm:w-auto justify-center bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 border border-secondary-300 dark:border-primary-600 px-5 py-2.5 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 transition shadow-sm inline-flex items-center gap-2 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Volver al Panel
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6 mb-6">
        <form method="GET" action="{{ route('staff.asistencia') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Filtro por Evento -->
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2 inline-flex items-center gap-2">
                        <svg viewBox="0 0 16 16" class="w-4 h-4" fill="currentColor">
                            <rect x="2" y="3" width="12" height="11" rx="1.5" fill="currentColor" opacity="0.3"/>
                            <rect x="2" y="2" width="12" height="3" rx="1" fill="currentColor"/>
                            <circle cx="5" cy="7" r="0.8" fill="currentColor"/>
                            <circle cx="8" cy="7" r="0.8" fill="currentColor"/>
                        </svg>
                        Evento
                    </label>
                    @if(auth()->user()->evento_id)
                        <div class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-500 dark:text-secondary-400 cursor-not-allowed">
                             {{ $eventos->first()->nombre ?? 'Evento Asignado' }}
                             <input type="hidden" name="evento_id" value="{{ auth()->user()->evento_id }}">
                        </div>
                    @else
                        <select name="evento_id" 
                                onchange="this.form.submit()"
                                class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                            <option value="">Todos los eventos</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}" {{ $evento_id == $evento->id ? 'selected' : '' }}>
                                    {{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }} ({{ $evento->tickets_count }} entradas)
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- Filtro por Ciclo -->
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2 inline-flex items-center gap-2">
                        <svg viewBox="0 0 16 16" class="w-4 h-4" fill="currentColor">
                            <path d="M4 2h8a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V4a2 2 0 012-2z" fill="none" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M4 6h8M4 10h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Filtrar por Ciclo
                    </label>
                    <select name="course" 
                            onchange="this.form.submit()"
                            class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                        <option value="">Todos los ciclos</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso }}" {{ request('course') == $curso ? 'selected' : '' }}>
                                {{ Str::limit($curso, 30) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Búsqueda -->
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2 inline-flex items-center gap-2">
                        <svg viewBox="0 0 16 16" class="w-4 h-4" fill="currentColor">
                            <circle cx="7" cy="7" r="5" fill="none" stroke="currentColor" stroke-width="2"/>
                            <path d="M11 11l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Buscar
                    </label>
                    <input type="text" 
                           id="buscar" 
                           placeholder="Nombre del asistente..."
                           class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de Asistencias (Desktop) -->
    <div class="hidden md:block bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 overflow-hidden">
        <!-- DEBUG INFO: Total Registros en Paginador: {{ $asistencias->total() }} | Count en esta página: {{ $asistencias->count() }} -->
        <div class="overflow-x-auto">
            <table class="w-full" id="tabla-asistencia">
                <thead class="bg-secondary-50 dark:bg-primary-800">
                    <tr>
                        <th class="hidden md:table-cell text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">#</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Hora</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Asistente</th>
                        <th class="hidden xl:table-cell text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Método</th>
                        <th class="hidden md:table-cell text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asistencias as $index => $asistencia)
                    @php
                        $user = $asistencia->ticket?->user ?? $asistencia->guest;
                        $userName = $user?->name ?? $user?->nombre ?? '';
                        
                        $ciclo = 'No especificado';
                        if ($asistencia->ticket && $asistencia->ticket->user) {
                            $reg = $asistencia->ticket->user->registrations->where('event_id', $asistencia->evento_id)->first();
                            $ciclo = $reg->course ?? 'No especificado';
                        } elseif ($asistencia->guest) {
                            $ciclo = $asistencia->guest->registration->course ?? 'No especificado';
                        }
                    @endphp
                    <tr class="border-b border-secondary-100 dark:border-primary-800 hover:bg-secondary-50 dark:hover:bg-primary-800/50 transition" data-nombre="{{ strtolower($userName) }}">
                        <td class="hidden md:table-cell py-4 px-6 text-sm text-secondary-600 dark:text-secondary-400">
                            {{ ($asistencias->currentPage() - 1) * $asistencias->perPage() + $index + 1 }}
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm font-medium text-secondary-900 dark:text-white">{{ $asistencia->created_at->format('H:i:s') }}</p>
                            <p class="text-xs text-secondary-600 dark:text-secondary-400">{{ $asistencia->created_at->format('d/m/Y') }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                @if($user && ($user->profile_photo_path ?? false))
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                     alt="Foto" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-primary-500">
                                @else
                                <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                                    {{ substr($userName ?: 'N', 0, 1) }}
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-secondary-900 dark:text-white">{{ $userName ?: 'N/A' }}</p>
                                    <p class="text-xs text-secondary-600 dark:text-secondary-400">{{ $ciclo ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="hidden xl:table-cell py-4 px-6">
                            <span class="px-3 py-1 text-xs font-medium rounded-full inline-flex items-center gap-1 {{ $asistencia->metodo === 'qr' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                @if($asistencia->metodo === 'qr')
                                    <svg viewBox="0 0 12 12" class="w-3 h-3" fill="currentColor">
                                        <rect x="1" y="1" width="4" height="4" rx="0.5"/>
                                        <rect x="7" y="1" width="4" height="4" rx="0.5"/>
                                        <rect x="1" y="7" width="4" height="4" rx="0.5"/>
                                    </svg>
                                    QR
                                @else
                                    <svg viewBox="0 0 12 12" class="w-3 h-3" fill="none">
                                        <path d="M2 6h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M7 3l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Manual
                                @endif
                            </span>
                        </td>
                        <td class="hidden md:table-cell py-4 px-6">
                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full inline-flex items-center gap-1">
                                <svg viewBox="0 0 12 12" class="w-3 h-3" fill="currentColor">
                                    <circle cx="6" cy="6" r="5"/>
                                    <path d="M4 6l1.5 1.5L9 4" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                                </svg>
                                Validado
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="inline-block">
                                <svg viewBox="0 0 64 64" class="w-16 h-16 animate-pulse" fill="none">
                                    <circle cx="32" cy="32" r="28" stroke="#94a3b8" stroke-width="2" fill="none"/>
                                    <circle cx="32" cy="32" r="20" stroke="#cbd5e1" stroke-width="2" fill="none"/>
                                    <path d="M32 16v16l8 8" stroke="#64748b" stroke-width="2.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="text-secondary-600 dark:text-secondary-400 mt-4">No hay asistencias registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación Desktop -->
        @if($asistencias->hasPages())
        <div class="bg-secondary-50 dark:bg-primary-800 px-6 py-4 border-t border-secondary-200 dark:border-primary-700">
            {{ $asistencias->links() }}
        </div>
        @endif
    </div>

     <!-- Vista Móvil (Cards) -->
     <div class="md:hidden space-y-4" id="cards-asistencia">
        @forelse($asistencias as $asistencia)
        @php
            $user = $asistencia->ticket?->user ?? $asistencia->guest;
            $userName = $user?->name ?? $user?->nombre ?? '';
            
            $ciclo = 'No especificado';
            if ($asistencia->ticket && $asistencia->ticket->user) {
                $reg = $asistencia->ticket->user->registrations->where('event_id', $asistencia->evento_id)->first();
                $ciclo = $reg->course ?? 'No especificado';
            } elseif ($asistencia->guest) {
                $ciclo = $asistencia->guest->registration->course ?? 'No especificado';
            }
        @endphp
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-4 transition-all hover:shadow-lg"
             data-nombre="{{ strtolower($userName) }}">
            
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center gap-3">
                    @if($user && ($user->profile_photo_path ?? false))
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                         alt="Foto" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-primary-500">
                    @else
                    <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($userName ?: 'N', 0, 1) }}
                    </div>
                    @endif
                    <div>
                        <h3 class="font-bold text-secondary-900 dark:text-white text-sm line-clamp-1">{{ $userName ?: 'N/A' }}</h3>
                        <p class="text-xs text-secondary-500 dark:text-secondary-400">{{ $ciclo ?: 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-secondary-900 dark:text-white">{{ $asistencia->created_at->format('H:i') }}</p>
                    <p class="text-xs text-secondary-500">{{ $asistencia->created_at->format('d/m') }}</p>
                </div>
            </div>
            
            <div class="flex justify-between items-center pt-2 border-t border-secondary-100 dark:border-primary-800 mt-2">
                <span class="px-2 py-1 text-xs font-medium rounded-full inline-flex items-center gap-1 {{ $asistencia->metodo === 'qr' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                    @if($asistencia->metodo === 'qr')
                        <svg viewBox="0 0 12 12" class="w-3 h-3" fill="currentColor"><rect x="1" y="1" width="4" height="4" rx="0.5"/><rect x="7" y="1" width="4" height="4" rx="0.5"/><rect x="1" y="7" width="4" height="4" rx="0.5"/></svg> QR
                    @else
                        <svg viewBox="0 0 12 12" class="w-3 h-3" fill="none"><path d="M2 6h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M7 3l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> Manual
                    @endif
                </span>
                
                <span class="text-green-600 dark:text-green-400 text-xs font-bold flex items-center gap-1">
                    <svg viewBox="0 0 12 12" class="w-3 h-3" fill="currentColor"><circle cx="6" cy="6" r="5"/><path d="M4 6l1.5 1.5L9 4" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                    Validado
                </span>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow p-6 text-center">
            <p class="text-secondary-500 dark:text-secondary-400">No hay asistencias registradas</p>
        </div>
        @endforelse

        <!-- Paginación Móvil -->
        @if($asistencias->hasPages())
        <div class="mt-4">
            {{ $asistencias->links() }}
        </div>
        @endif
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Total Validado</h3>
                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg viewBox="0 0 20 20" class="w-5 h-5" fill="white">
                        <circle cx="10" cy="10" r="8" stroke="white" stroke-width="2" fill="none"/>
                        <path d="M6 10l2 2 5-5" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">
                {{ $asistencias->total() }} <span class="text-lg text-secondary-500 font-normal">/ {{ $total_registrados }} (Registrados)</span>
            </p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Última Hora</h3>
                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg viewBox="0 0 20 20" class="w-5 h-5" fill="white">
                        <circle cx="10" cy="10" r="8" stroke="white" stroke-width="2" fill="none"/>
                        <path d="M10 5v5l3 3" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">
                {{ \App\Models\Asistencia::where('created_at', '>=', now()->subHour())->count() }}
            </p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Promedio/Hora</h3>
                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg viewBox="0 0 20 20" class="w-5 h-5" fill="white">
                        <rect x="3" y="10" width="3" height="7" rx="1" fill="white"/>
                        <rect x="8.5" y="6" width="3" height="11" rx="1" fill="white"/>
                        <rect x="14" y="3" width="3" height="14" rx="1" fill="white"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">
                {{ round(\App\Models\Asistencia::whereDate('created_at', today())->count() / max(1, now()->hour)) }}
            </p>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Búsqueda en tiempo real (Solo por nombre)
    document.getElementById('buscar').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        // Seleccionar filas de tabla y tarjetas móviles
        const rows = document.querySelectorAll('#tabla-asistencia tbody tr, #cards-asistencia > div');
        
        rows.forEach(row => {
            // Ignorar si no tiene datos (mensajes de vacío, etc)
            if (!row.hasAttribute('data-nombre')) return;

            const nombre = row.getAttribute('data-nombre') || '';
            // const email = row.getAttribute('data-email') || ''; // Email deshabilitado por privacidad
            
            if (nombre.includes(search)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
</x-app-layout>