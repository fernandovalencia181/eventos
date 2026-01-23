@extends('staff.layout')

@section('title', 'Control d\'Aforament')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-xl transform hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">Control d'Aforament</h1>
                    <p class="text-secondary-600 dark:text-secondary-400 mt-1">Monitoratge en temps real de la capacitat dels esdeveniments</p>
                </div>
            </div>
            
            <!-- Botón de exportar -->
            <div class="flex gap-3">
                <a href="{{ route('staff.exportar-aforo') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all font-bold shadow-lg transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Exportar CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Selector de Evento -->
    <div class="backdrop-blur-sm bg-white/70 dark:bg-primary-900/70 rounded-2xl shadow-xl p-6 border border-white/20 dark:border-primary-800/50 mb-6">
        <label class="block text-sm font-bold text-secondary-900 dark:text-white mb-3">🎯 Seleccionar Esdeveniment</label>
        <select id="evento_selector" class="w-full md:w-1/2 bg-white/90 dark:bg-primary-800/90 border-2 border-cyan-300 dark:border-cyan-700 rounded-xl px-5 py-4 text-secondary-900 dark:text-white focus:ring-2 focus:ring-cyan-500 transition-all shadow-lg font-medium">
            <option value="">📊 Tots els esdeveniments actius</option>
            @foreach($eventos as $evento)
                <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}</option>
            @endforeach
        </select>
    </div>

    <!-- Grid de Eventos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventos as $evento)
        <div class="relative overflow-hidden backdrop-blur-sm bg-gradient-to-br from-white/80 to-white/60 dark:from-primary-900/80 dark:to-primary-900/60 rounded-2xl shadow-2xl border border-white/30 dark:border-primary-800/50 transform hover:scale-105 transition-all duration-300">
            <!-- Header del evento con degradado -->
            <div class="bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-600 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                <div class="relative z-10">
                    <h3 class="text-xl font-black mb-2 drop-shadow-lg">{{ $evento->nombre }}</h3>
                    <p class="text-sm text-cyan-100 font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        {{ $evento->fecha->format('d M Y - H:i') }}
                    </p>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="p-6">
                <!-- Barra de progreso mejorada -->
                @php
                    $aforo_usado = $evento->checkins_count ?? 0;
                    $aforo_total = $evento->aforo ?? 100;
                    $porcentaje = $aforo_total > 0 ? ($aforo_usado / $aforo_total) * 100 : 0;
                    $color = $porcentaje >= 90 ? 'red' : ($porcentaje >= 75 ? 'yellow' : 'green');
                @endphp

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-bold text-secondary-900 dark:text-white">💫 Ocupació</span>
                        <span class="text-lg font-black {{ $color === 'red' ? 'text-red-600 dark:text-red-400' : ($color === 'yellow' ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                            {{ number_format($porcentaje, 1) }}%
                        </span>
                    </div>
                    <div class="relative w-full bg-secondary-200 dark:bg-primary-800 rounded-full h-5 overflow-hidden shadow-inner">
                        @php
                            $width = $porcentaje > 100 ? 100 : $porcentaje;
                        @endphp
                        <div class="absolute inset-0 {{ $color === 'red' ? 'bg-gradient-to-r from-red-500 to-rose-600' : ($color === 'yellow' ? 'bg-gradient-to-r from-yellow-500 to-amber-600' : 'bg-gradient-to-r from-green-500 to-emerald-600') }} transition-all duration-500 shadow-lg" style="width: {{ $width }}%;">
                            <div class="h-full w-full animate-pulse opacity-30 bg-white"></div>
                        </div>
                    </div>
                </div>

                <!-- Números con diseño moderno -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="relative overflow-hidden text-center p-5 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl shadow-lg transform hover:scale-105 transition-all">
                        <p class="text-4xl font-black text-white drop-shadow-lg">{{ $aforo_usado }}</p>
                        <p class="text-xs text-cyan-100 font-bold mt-1">👥 Dins</p>
                    </div>
                    <div class="relative overflow-hidden text-center p-5 bg-gradient-to-br from-slate-500 to-slate-700 rounded-xl shadow-lg transform hover:scale-105 transition-all">
                        <p class="text-4xl font-black text-white drop-shadow-lg">{{ $aforo_total }}</p>
                        <p class="text-xs text-slate-200 font-bold mt-1">🎯 Capacitat</p>
                    </div>
                </div>

                <!-- Disponibilidad con mejor diseño -->
                <div class="text-center p-4 rounded-xl shadow-lg {{ $porcentaje >= 90 ? 'bg-gradient-to-r from-red-500 to-rose-600 text-white' : 'bg-gradient-to-r from-green-500 to-emerald-600 text-white' }}">
                    <p class="text-sm font-black">
                        @if($porcentaje >= 100)
                            ⛔ AFORAMENT COMPLET
                        @elseif($porcentaje >= 90)
                            ⚠️ {{ $aforo_total - $aforo_usado }} PLACES RESTANTS
                        @else
                            ✅ {{ $aforo_total - $aforo_usado }} PLACES DISPONIBLES
                        @endif
                    </p>
                </div>

                <!-- Botón de acción mejorado -->
                <div class="mt-6 pt-4 border-t border-secondary-200 dark:border-primary-800">
                    <a href="{{ route('staff.scanner') }}" class="block text-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all font-bold shadow-lg transform hover:scale-105">
                        📱 Anar a l'Escàner
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <div class="text-8xl mb-6 animate-bounce">📊</div>
            <p class="text-xl font-bold text-secondary-900 dark:text-white mb-2">No hi ha esdeveniments actius</p>
            <p class="text-secondary-600 dark:text-secondary-400">Quan es creïn esdeveniments apareixeran aquí</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
