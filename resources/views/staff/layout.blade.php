<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff Panel') - EventosU</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-secondary-50 dark:bg-primary-950 text-secondary-900 dark:text-gray-100 min-h-screen transition-colors duration-300">
    
    <!-- Navegación Staff -->
    <nav class="bg-primary-900 border-b border-primary-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('staff.index') }}" class="flex items-center gap-2 group">
                            <div class="w-8 h-8 bg-primary-800 text-primary-200 rounded-lg flex items-center justify-center group-hover:bg-primary-700 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-white tracking-tight">Staff Panel</span>
                        </a>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <a href="{{ route('staff.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('staff.index') ? 'border-primary-500 text-white focus:outline-none focus:border-primary-400' : 'border-transparent text-primary-300 hover:text-white hover:border-primary-400 focus:outline-none focus:text-white focus:border-primary-400' }}">
                            Dashboard
                        </a>
                        
                        <!-- Check-in y Validación -->
                        <div class="relative inline-flex items-center px-1 pt-1 border-b-2 border-transparent" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center text-sm font-medium leading-5 text-primary-300 hover:text-white focus:outline-none transition duration-150 ease-in-out gap-1">
                                Check-in
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 top-full mt-2 w-48 bg-primary-800 rounded-md shadow-lg py-1 z-50 border border-primary-700">
                                <a href="{{ route('staff.scanner') }}" class="block px-4 py-2 text-sm text-primary-200 hover:bg-primary-700 hover:text-white">Escáner QR</a>
                            </div>
                        </div>

                        <!-- Gestión Operativa -->
                        <div class="relative inline-flex items-center px-1 pt-1 border-b-2 border-transparent" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center text-sm font-medium leading-5 text-primary-300 hover:text-white focus:outline-none transition duration-150 ease-in-out gap-1">
                                Gestión
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 top-full mt-2 w-48 bg-primary-800 rounded-md shadow-lg py-1 z-50 border border-primary-700">
                                <a href="{{ route('staff.asistencia') }}" class="block px-4 py-2 text-sm text-primary-200 hover:bg-primary-700 hover:text-white">Assistència</a>
                                <a href="{{ route('staff.incidencias') }}" class="block px-4 py-2 text-sm text-primary-200 hover:bg-primary-700 hover:text-white">Incidencias</a>
                            </div>
                        </div>

                        <!-- Logística -->
                        <a href="{{ route('staff.invitados') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('staff.invitados') ? 'border-primary-500 text-white focus:outline-none focus:border-primary-400' : 'border-transparent text-primary-300 hover:text-white hover:border-primary-400 focus:outline-none focus:text-white focus:border-primary-400' }}">
                            Invitados
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button x-data="{ 
                                darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                                toggle() {
                                    this.darkMode = !this.darkMode;
                                    if (this.darkMode) {
                                        document.documentElement.classList.add('dark');
                                        localStorage.theme = 'dark';
                                    } else {
                                        document.documentElement.classList.remove('dark');
                                        localStorage.theme = 'light';
                                    }
                                }
                            }" 
                            @click="toggle()"
                            class="text-primary-200 hover:text-white transition focus:outline-none p-2 rounded-lg hover:bg-primary-800"
                            title="Cambiar tema">
                        <!-- Sun Icon (Show when Dark) -->
                        <svg x-show="darkMode" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                        <!-- Moon Icon (Show when Light) -->
                        <svg x-show="!darkMode" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-primary-200 hover:text-white transition focus:outline-none gap-2">
                            <span class="text-right hidden md:block">
                                <div class="text-xs text-primary-400">Hola,</div>
                                <div class="font-bold">{{ Auth::user()->name }}</div>
                            </span>
                            <img class="h-8 w-8 rounded-full object-cover border-2 border-primary-100" 
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=6366f1&background=eef2ff" 
                                alt="{{ Auth::user()->name }}" />
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-primary-800 rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-primary-200 hover:bg-primary-700 hover:text-white">Volver al sitio</a>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <a href="{{ route('logout') }}" @click.prevent="$root.submit();" class="block px-4 py-2 text-sm text-red-300 hover:bg-primary-700 hover:text-white">Cerrar Sesión</a>
                            </form>
                        </div>
                    </div>
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
                <div class="border-t border-primary-700 my-2"></div>
                <p class="text-xs text-primary-400 uppercase font-semibold">Gestión</p>
                <a href="{{ route('staff.asistencia') }}" class="block text-primary-200 hover:text-white py-2 pl-4">Assistència</a>
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
</body>
</html>
