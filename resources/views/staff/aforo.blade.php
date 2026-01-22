@extends('staff.layout')

@section('title', 'Control de Aforo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-900 dark:text-white">Control de Aforo</h1>
        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Monitoreo en tiempo real de la capacidad de eventos</p>
    </div>

    <!-- Selector de Evento -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800 mb-6">
        <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Seleccionar Evento</label>
        <select id="evento_selector" class="w-full md:w-1/2 bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-3 text-secondary-900 dark:text-white">
            <option value="">Todos los eventos activos</option>
            @foreach($eventos as $evento)
                <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}</option>
            @endforeach
        </select>
    </div>

    <!-- Grid de Eventos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventos as $evento)
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 overflow-hidden">
            <!-- Header del evento -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-6 text-white">
                <h3 class="text-xl font-bold mb-2">{{ $evento->nombre }}</h3>
                <p class="text-sm text-primary-100">{{ $evento->fecha->format('d M Y - H:i') }}</p>
            </div>

            <!-- Estadísticas -->
            <div class="p-6">
                <!-- Barra de progreso -->
                @php
                    $aforo_usado = $evento->checkins_count ?? 0;
                    $aforo_total = $evento->aforo ?? 100;
                    $porcentaje = $aforo_total > 0 ? ($aforo_usado / $aforo_total) * 100 : 0;
                    $color = $porcentaje >= 90 ? 'red' : ($porcentaje >= 75 ? 'yellow' : 'green');
                @endphp

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-secondary-900 dark:text-white">Ocupación</span>
                        <span class="text-sm font-bold {{ $color === 'red' ? 'text-red-600' : ($color === 'yellow' ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ number_format($porcentaje, 1) }}%
                        </span>
                    </div>
                    <div class="w-full bg-secondary-200 dark:bg-primary-800 rounded-full h-4 overflow-hidden">
                        @php
                            $width = $porcentaje > 100 ? 100 : $porcentaje;
                        @endphp
                        <div class="h-full {{ $color === 'red' ? 'bg-red-500' : ($color === 'yellow' ? 'bg-yellow-500' : 'bg-green-500') }} transition-all duration-500" style="width: {{ $width }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Números -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-4 bg-secondary-50 dark:bg-primary-800 rounded-lg">
                        <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $aforo_usado }}</p>
                        <p class="text-xs text-secondary-600 dark:text-secondary-400">Personas dentro</p>
                    </div>
                    <div class="text-center p-4 bg-secondary-50 dark:bg-primary-800 rounded-lg">
                        <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $aforo_total }}</p>
                        <p class="text-xs text-secondary-600 dark:text-secondary-400">Capacidad total</p>
                    </div>
                </div>

                <!-- Disponibilidad -->
                <div class="text-center p-3 rounded-lg {{ $porcentaje >= 90 ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' }}">
                    <p class="text-sm font-semibold">
                        @if($porcentaje >= 100)
                            ⛔ AFORO COMPLETO
                        @elseif($porcentaje >= 90)
                            ⚠️ {{ $aforo_total - $aforo_usado }} plazas restantes
                        @else
                            ✅ {{ $aforo_total - $aforo_usado }} plazas disponibles
                        @endif
                    </p>
                </div>

                <!-- Botones de acción -->
                <div class="mt-4 pt-4 border-t border-secondary-200 dark:border-primary-800">
                    <a href="{{ route('staff.scanner') }}" class="block text-center bg-primary-600 text-white py-2 rounded-lg hover:bg-primary-700 transition">
                        Ir al Scanner
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="text-6xl mb-4">📊</div>
            <p class="text-secondary-600 dark:text-secondary-400">No hay eventos activos</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
