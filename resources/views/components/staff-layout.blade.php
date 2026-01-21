@props(['evento'])

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Staff - {{ $evento->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white">
    
    @include('navigation-menu')

    <!-- Información del Evento -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 mt-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-secondary-100">
            <h2 class="text-3xl font-bold mb-2 text-secondary-900">{{ $evento->nombre }}</h2>
            <p class="text-lg text-secondary-600">📅 {{ $evento->fecha->format('d/m/Y H:i') }} | 📍 {{ $evento->lugar }}</p>
        </div>
    </div>

    <!-- Pestañas de Navegación -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-2 border border-secondary-100">
            <div class="flex gap-2 overflow-x-auto">
                <a 
                    href="{{ route('staff.evento.validacion', $evento->id) }}" 
                    class="flex-1 px-6 py-3 text-center rounded-xl font-medium transition-all
                        {{ request()->routeIs('staff.evento.validacion') 
                            ? 'bg-primary-600 text-white shadow-md' 
                            : 'bg-secondary-50 text-secondary-700 hover:bg-secondary-100' }}"
                >
                    Check-in
                </a>
                <a 
                    href="{{ route('staff.evento.aforo', $evento->id) }}" 
                    class="flex-1 px-6 py-3 text-center rounded-xl font-medium transition-all
                        {{ request()->routeIs('staff.evento.aforo') 
                            ? 'bg-primary-600 text-white shadow-md' 
                            : 'bg-secondary-50 text-secondary-700 hover:bg-secondary-100' }}"
                >
                    Aforo
                </a>
                <a 
                    href="{{ route('staff.evento.incidencias', $evento->id) }}" 
                    class="flex-1 px-6 py-3 text-center rounded-xl font-medium transition-all
                        {{ request()->routeIs('staff.evento.incidencias') 
                            ? 'bg-primary-600 text-white shadow-md' 
                            : 'bg-secondary-50 text-secondary-700 hover:bg-secondary-100' }}"
                >
                    Incidencias
                </a>
                <a 
                    href="{{ route('staff.evento.invitados', $evento->id) }}" 
                    class="flex-1 px-6 py-3 text-center rounded-xl font-medium transition-all
                        {{ request()->routeIs('staff.evento.invitados') 
                            ? 'bg-primary-600 text-white shadow-md' 
                            : 'bg-secondary-50 text-secondary-700 hover:bg-secondary-100' }}"
                >
                    Invitados
                </a>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
