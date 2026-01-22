<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff Panel') - EventosU</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-secondary-50 dark:bg-primary-950 text-secondary-900 dark:text-gray-100 min-h-screen">
    
    <!-- Navegación Staff -->
    <nav class="bg-primary-900 border-b border-primary-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('staff.index') }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-primary-800 text-primary-200 rounded-lg flex items-center justify-center group-hover:bg-primary-700 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">Staff Panel</span>
                    </a>

                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('staff.index') }}" class="text-primary-200 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('staff.index') ? 'bg-primary-800' : '' }}">
                            Dashboard
                        </a>
                        
                        <!-- Check-in y Validación -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-primary-200 hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center gap-1">
                                Check-in
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-primary-800 rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('staff.scanner') }}" class="block px-4 py-2 text-sm text-primary-200 hover:bg-primary-700 hover:text-white">Escáner QR</a>
                                <a href="{{ route('staff.validacion') }}" class="block px-4 py-2 text-sm text-primary-200 hover:bg-primary-700 hover:text-white">Validación Manual</a>
                            </div>
                        </div>

                        <!-- Gestión Operativa -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-primary-200 hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center gap-1">
                                Gestión
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-primary-800 rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('staff.aforo') }}" class="block px-4 py-2 text-sm text-primary-200 hover:bg-primary-700 hover:text-white">Control de Aforo</a>
                                <a href="{{ route('staff.incidencias') }}" class="block px-4 py-2 text-sm text-primary-200 hover:bg-primary-700 hover:text-white">Incidencias</a>
                            </div>
                        </div>

                        <!-- Logística -->
                        <a href="{{ route('staff.invitados') }}" class="text-primary-200 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('staff.invitados') ? 'bg-primary-800' : '' }}">
                            Invitados
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-primary-300 text-sm">{{ Auth::user()->name }}</span>
                    <a href="{{ route('home') }}" class="text-primary-200 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        Volver al sitio
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" x-data="{ open: false }">
            <button @click="open = !open" class="w-full px-4 py-2 text-left text-primary-200 hover:bg-primary-800">
                <svg class="w-6 h-6 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                Menú
            </button>
            <div x-show="open" @click.away="open = false" class="bg-primary-800 px-4 py-2 space-y-1">
                <a href="{{ route('staff.index') }}" class="block text-primary-200 hover:text-white py-2">Dashboard</a>
                <div class="border-t border-primary-700 my-2"></div>
                <p class="text-xs text-primary-400 uppercase font-semibold">Check-in</p>
                <a href="{{ route('staff.scanner') }}" class="block text-primary-200 hover:text-white py-2 pl-4">Escáner QR</a>
                <a href="{{ route('staff.validacion') }}" class="block text-primary-200 hover:text-white py-2 pl-4">Validación Manual</a>
                <div class="border-t border-primary-700 my-2"></div>
                <p class="text-xs text-primary-400 uppercase font-semibold">Gestión</p>
                <a href="{{ route('staff.aforo') }}" class="block text-primary-200 hover:text-white py-2 pl-4">Control de Aforo</a>
                <a href="{{ route('staff.incidencias') }}" class="block text-primary-200 hover:text-white py-2 pl-4">Incidencias</a>
                <div class="border-t border-primary-700 my-2"></div>
                <a href="{{ route('staff.invitados') }}" class="block text-primary-200 hover:text-white py-2">Invitados</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="py-8">
        @yield('content')
    </main>

    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
