<nav class="bg-primary-900 border-b border-primary-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-primary-800 text-primary-200 rounded-lg flex items-center justify-center group-hover:bg-primary-700 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">EventosU</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Inicio
                    </x-nav-link>
                    
                    @auth
                        @if(Auth::user()->isAdmin())
                            <x-nav-link :href="route('eventos.create')" :active="request()->routeIs('eventos.create')">
                                Crear Evento
                            </x-nav-link>
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                Panel Admin
                            </x-nav-link>
                            <x-nav-link :href="route('calendario')" :active="request()->routeIs('calendario')">
                                Calendario
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('mis.entradas')" :active="request()->routeIs('mis.entradas')">
                                Mis Entradas
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                
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

                @auth
                <div class="ml-3 relative" x-data="{ open: false }">
                    
                    <button @click="open = !open" class="flex items-center text-sm font-medium text-primary-200 hover:text-white transition focus:outline-none">
                        <span class="mr-2 text-right">
                            <div class="text-xs text-primary-400">Hola,</div>
                            <div class="font-bold">{{ Auth::user()->name }}</div>
                        </span>
                        <img class="h-8 w-8 rounded-full object-cover border-2 border-primary-100" 
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=6366f1&background=eef2ff" 
                            alt="{{ Auth::user()->name }}" />
                    </button>
                    
                    <div x-show="open" 
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-primary-800 rounded-xl shadow-lg py-2 border border-primary-700 z-50"
                        style="display: none;"> <div class="px-4 py-2 border-b border-primary-700">
                            <p class="text-xs text-primary-400">Gestionar cuenta</p>
                        </div>
                        
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-primary-200 hover:bg-primary-700 hover:text-white transition">Perfil</a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-danger hover:bg-red-50 transition">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-primary-200 hover:text-white transition">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-lg shadow-md hover:bg-primary-500 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        Registrarse
                    </a>
                @endauth
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="inline-flex items-center justify-center p-2 rounded-md text-primary-300 hover:text-white hover:bg-primary-800 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden sm:hidden bg-primary-900 border-b border-primary-800">
        <div class="pt-2 pb-3 space-y-1">
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
                    class="w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-primary-300 hover:bg-primary-800 hover:border-primary-500 hover:text-white flex items-center gap-2">
                <div class="flex items-center gap-2">
                    <svg x-show="darkMode" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <svg x-show="!darkMode" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                    <span x-text="darkMode ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"></span>
                </div>
            </button>
            <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-primary-300 hover:bg-primary-800 hover:border-primary-500 hover:text-white">Inicio</a>
            @auth
                 @if(Auth::user()->isAdmin())
                    <a href="{{ route('eventos.create') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-primary-300 hover:bg-primary-800 hover:border-primary-500 hover:text-white">Crear Evento</a>
                    <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-primary-300 hover:bg-primary-800 hover:border-primary-500 hover:text-white">Panel Admin</a>
                    <a href="{{ route('calendario') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-primary-300 hover:bg-primary-800 hover:border-primary-500 hover:text-white">Calendario</a>
                @else
                    <a href="{{ route('mis.entradas') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-primary-300 hover:bg-primary-800 hover:border-primary-500 hover:text-white">Mis Entradas</a>
                @endif
            @endauth
        </div>
        <div class="pt-4 pb-4 border-t border-primary-800">
            @auth
                <div class="flex items-center px-4">
                    <div class="ml-3">
                        <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-primary-300">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-primary-300 hover:text-white hover:bg-primary-800">Cerrar Sesión</button>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 px-4">
                    <a href="{{ route('login') }}" class="block text-center w-full py-2 text-white border border-primary-600 rounded-lg mb-2 hover:bg-primary-800">Login</a>
                    <a href="{{ route('register') }}" class="block text-center w-full py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-500">Registrarse</a>
                </div>
            @endauth
        </div>
    </div>
</nav>