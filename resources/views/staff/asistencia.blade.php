@extends('staff.layout')

@section('title', 'Llista d\'Assistència')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Capçalera -->
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="relative w-16 h-16 bg-gradient-to-br from-green-400 via-emerald-500 to-teal-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:-rotate-3 transition-all duration-300">
                <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                <div class="relative z-10">
                    <svg viewBox="0 0 32 32" class="w-8 h-8" fill="none">
                        <rect x="6" y="4" width="20" height="24" rx="2" stroke="white" stroke-width="2.5" fill="white" opacity="0.3"/>
                        <line x1="10" y1="10" x2="22" y2="10" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="10" y1="16" x2="22" y2="16" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="10" y1="22" x2="18" y2="22" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="24" cy="24" r="6" fill="#10b981"/>
                        <path d="M21 24l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Llista d'Assistència en Viu</h1>
                <p class="text-secondary-600 dark:text-secondary-400 mt-2">Cerca manual per nom o matrícula</p>
            </div>
        </div>
        <a href="{{ route('staff.exportar', ['evento_id' => $evento_id]) }}" 
           class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl hover:from-green-700 hover:to-emerald-700 transition shadow-lg transform hover:scale-105 inline-flex items-center gap-2">
            <svg viewBox="0 0 20 20" class="w-5 h-5" fill="white">
                <path d="M10 2v10m0 0l-3-3m3 3l3-3" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
                <rect x="3" y="14" width="14" height="4" rx="1" fill="white"/>
            </svg>
            Exportar CSV
        </a>
    </div>

    <!-- Filtres -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6 mb-6">
        <form method="GET" action="{{ route('staff.asistencia') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Filtre per Esdeveniment -->
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2 inline-flex items-center gap-2">
                        <svg viewBox="0 0 16 16" class="w-4 h-4" fill="currentColor">
                            <rect x="2" y="3" width="12" height="11" rx="1.5" fill="currentColor" opacity="0.3"/>
                            <rect x="2" y="2" width="12" height="3" rx="1" fill="currentColor"/>
                            <circle cx="5" cy="7" r="0.8" fill="currentColor"/>
                            <circle cx="8" cy="7" r="0.8" fill="currentColor"/>
                        </svg>
                        Esdeveniment
                    </label>
                    <select name="evento_id" 
                            onchange="this.form.submit()"
                            class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                        <option value="">Tots els esdeveniments</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}" {{ $evento_id == $evento->id ? 'selected' : '' }}>
                                {{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Cerca -->
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2 inline-flex items-center gap-2">
                        <svg viewBox="0 0 16 16" class="w-4 h-4" fill="currentColor">
                            <circle cx="7" cy="7" r="5" fill="none" stroke="currentColor" stroke-width="2"/>
                            <path d="M11 11l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Cercar
                    </label>
                    <input type="text" 
                           id="buscar" 
                           placeholder="Nom, matrícula o email..."
                           class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
            </div>
        </form>
    </div>

    <!-- Taula d'Assistències -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="tabla-asistencia">
                <thead class="bg-secondary-50 dark:bg-primary-800">
                    <tr>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">#</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Hora</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Assistant</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Matrícula</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Esdeveniment</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Mètode</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Validat per</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-secondary-900 dark:text-white">Estat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asistencias as $index => $asistencia)
                    <tr class="border-b border-secondary-100 dark:border-primary-800 hover:bg-secondary-50 dark:hover:bg-primary-800/50 transition" data-nombre="{{ strtolower($asistencia->ticket->user->name ?? '') }}" data-matricula="{{ strtolower($asistencia->ticket->user->matricula ?? '') }}" data-email="{{ strtolower($asistencia->ticket->user->email ?? '') }}">
                        <td class="py-4 px-6 text-sm text-secondary-600 dark:text-secondary-400">
                            {{ ($asistencias->currentPage() - 1) * $asistencias->perPage() + $index + 1 }}
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm font-medium text-secondary-900 dark:text-white">{{ $asistencia->created_at->format('H:i:s') }}</p>
                            <p class="text-xs text-secondary-600 dark:text-secondary-400">{{ $asistencia->created_at->format('d/m/Y') }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                @if($asistencia->ticket->user->profile_photo_path ?? false)
                                <img src="{{ asset('storage/' . $asistencia->ticket->user->profile_photo_path) }}" 
                                     alt="Foto" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-primary-500">
                                @else
                                <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                                    {{ substr($asistencia->ticket->user->name ?? 'N', 0, 1) }}
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-secondary-900 dark:text-white">{{ $asistencia->ticket->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-secondary-600 dark:text-secondary-400">{{ $asistencia->ticket->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 text-sm font-mono bg-secondary-100 dark:bg-primary-800 text-secondary-900 dark:text-white rounded-full">
                                {{ $asistencia->ticket->user->matricula ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-sm text-secondary-900 dark:text-white">
                            {{ $asistencia->evento->nombre }}
                        </td>
                        <td class="py-4 px-6">
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
                        <td class="py-4 px-6 text-sm text-secondary-600 dark:text-secondary-400">
                            {{ $asistencia->staff->nombre ?? 'N/A' }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full inline-flex items-center gap-1">
                                <svg viewBox="0 0 12 12" class="w-3 h-3" fill="currentColor">
                                    <circle cx="6" cy="6" r="5"/>
                                    <path d="M4 6l1.5 1.5L9 4" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                                </svg>
                                Validat
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center">
                            <div class="inline-block">
                                <svg viewBox="0 0 64 64" class="w-16 h-16 animate-pulse" fill="none">
                                    <circle cx="32" cy="32" r="28" stroke="#94a3b8" stroke-width="2" fill="none"/>
                                    <circle cx="32" cy="32" r="20" stroke="#cbd5e1" stroke-width="2" fill="none"/>
                                    <path d="M32 16v16l8 8" stroke="#64748b" stroke-width="2.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="text-secondary-600 dark:text-secondary-400 mt-4">No hi ha assistències registrades</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginació -->
        @if($asistencias->hasPages())
        <div class="bg-secondary-50 dark:bg-primary-800 px-6 py-4 border-t border-secondary-200 dark:border-primary-700">
            {{ $asistencias->links() }}
        </div>
        @endif
    </div>

    <!-- Estadístiques Ràpides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Total Validats</h3>
                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg viewBox="0 0 20 20" class="w-5 h-5" fill="white">
                        <circle cx="10" cy="10" r="8" stroke="white" stroke-width="2" fill="none"/>
                        <path d="M6 10l2 2 5-5" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $asistencias->total() }}</p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Última Hora</h3>
                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
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
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Mitjana/Hora</h3>
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
@endsection

@section('scripts')
<script>
    // Búsqueda en tiempo real
    document.getElementById('buscar').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#tabla-asistencia tbody tr');
        
        rows.forEach(row => {
            const nombre = row.getAttribute('data-nombre') || '';
            const matricula = row.getAttribute('data-matricula') || '';
            const email = row.getAttribute('data-email') || '';
            
            if (nombre.includes(search) || matricula.includes(search) || email.includes(search)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
