<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Staff - {{ $evento->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Navegación -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        🎫 Panel Staff
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        👤 {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Información del Evento -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <h2 class="text-3xl font-bold mb-2">{{ $evento->nombre }}</h2>
            <p class="text-lg">📅 {{ $evento->fecha->format('d/m/Y H:i') }} | 📍 {{ $evento->lugar }}</p>
        </div>
    </div>

    <!-- Pestañas de Navegación -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-2">
            <div class="flex gap-2 overflow-x-auto">
                <a 
                    href="{{ route('staff.evento.validacion', $evento->id) }}" 
                    class="flex-1 px-6 py-3 text-center rounded-lg font-medium transition-colors
                        {{ request()->routeIs('staff.evento.validacion') 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                >
                    ✅ Check-in
                </a>
                <a 
                    href="{{ route('staff.evento.aforo', $evento->id) }}" 
                    class="flex-1 px-6 py-3 text-center rounded-lg font-medium transition-colors
                        {{ request()->routeIs('staff.evento.aforo') 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                >
                    📊 Aforo
                </a>
                <a 
                    href="{{ route('staff.evento.incidencias', $evento->id) }}" 
                    class="flex-1 px-6 py-3 text-center rounded-lg font-medium transition-colors
                        {{ request()->routeIs('staff.evento.incidencias') 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                >
                    🚨 Incidencias
                </a>
                <a 
                    href="{{ route('staff.evento.invitados', $evento->id) }}" 
                    class="flex-1 px-6 py-3 text-center rounded-lg font-medium transition-colors
                        {{ request()->routeIs('staff.evento.invitados') 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                >
                    🌟 Invitados
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
