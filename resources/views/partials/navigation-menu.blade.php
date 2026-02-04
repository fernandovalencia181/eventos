<nav x-data="{ open: false }" class="bg-gradient-to-r from-primary-950/90 to-primary-800/90 backdrop-blur-md border-b border-primary-700/30 sticky top-0 z-50 transition-all duration-300 shadow-lg">
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

                <div class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex">
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
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                Usuarios
                            </x-nav-link>
                            <x-nav-link :href="route('admin.staff.index')" :active="request()->routeIs('admin.staff.*')">
                                Equipo Staff
                            </x-nav-link>
                            <x-nav-link :href="route('calendario')" :active="request()->routeIs('calendario')">
                                Calendario
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->isStaff())
                            <x-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.index')">
                                Panel Staff
                            </x-nav-link>
                            
                            <x-nav-link :href="route('staff.scanner')" :active="request()->routeIs('staff.scanner')">
                                Escáner
                            </x-nav-link>
                            <x-nav-link :href="route('staff.asistencia')" :active="request()->routeIs('staff.asistencia')">
                                Asistencia
                            </x-nav-link>
                            @if(Auth::user()->hasPermission('access_guests'))
                                <x-nav-link :href="route('staff.invitados')" :active="request()->routeIs('staff.invitados')">
                                    Invitados
                                </x-nav-link>
                            @endif
                        @endif

                        @if(!Auth::user()->isAdmin() && !Auth::user()->isStaff())
                            <x-nav-link :href="route('mis.entradas')" :active="request()->routeIs('mis.entradas')">
                                Mis Entradas
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden lg:flex lg:items-center lg:ml-6 space-x-4">
                
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

            <!-- Hamburger Button -->
            <div class="-mr-2 flex items-center lg:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-primary-300 hover:text-white hover:bg-primary-800 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         class="lg:hidden fixed top-16 left-0 w-full h-[calc(100vh-4rem)] overflow-y-auto bg-primary-900/95 backdrop-blur-xl border-t border-white/10 shadow-2xl z-50 pb-10"
         style="display: none;">
        
        <div class="pt-2 pb-3 space-y-1 px-4">
            
            <a href="{{ route('home') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('home') ? 'bg-white/10 text-white shadow-inner' : 'text-primary-100 hover:bg-white/5 hover:text-white' }} transition-all">Inicio</a>
            
            @auth
                 @if(Auth::user()->isAdmin())
                    <a href="{{ route('eventos.create') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('eventos.create') ? 'bg-white/10 text-white shadow-inner' : 'text-primary-100 hover:bg-white/5 hover:text-white' }} transition-all">Crear Evento</a>
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white shadow-inner' : 'text-primary-100 hover:bg-white/5 hover:text-white' }} transition-all">Panel Admin</a>
                    <a href="{{ route('users.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('users.*') ? 'bg-white/10 text-white shadow-inner' : 'text-primary-100 hover:bg-white/5 hover:text-white' }} transition-all">Usuarios</a>
                    <a href="{{ route('admin.staff.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('admin.staff.*') ? 'bg-white/10 text-white shadow-inner' : 'text-primary-100 hover:bg-white/5 hover:text-white' }} transition-all">Equipo Staff</a>
                    <a href="{{ route('calendario') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('calendario') ? 'bg-white/10 text-white shadow-inner' : 'text-primary-100 hover:bg-white/5 hover:text-white' }} transition-all">Calendario</a>
                @endif
                
                @if(Auth::user()->isStaff())
                    <a href="{{ route('staff.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('staff.index') ? 'bg-white/10 text-white shadow-inner' : 'text-primary-100 hover:bg-white/5 hover:text-white' }} transition-all">Panel Staff</a>
                    <div class="pl-4 space-y-1 mt-1 border-l-2 border-white/20">
                        <a href="{{ route('staff.scanner') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('staff.scanner') ? 'text-white' : 'text-primary-200 hover:text-white' }}">Escáner</a>
                        <a href="{{ route('staff.asistencia') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('staff.asistencia') ? 'text-white' : 'text-primary-200 hover:text-white' }}">Asistencia</a>
                        @if(Auth::user()->hasPermission('access_guests'))
                            <a href="{{ route('staff.invitados') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('staff.invitados') ? 'text-white' : 'text-primary-200 hover:text-white' }}">Invitados</a>
                        @endif
                    </div>
                @endif

                @if(!Auth::user()->isAdmin() && !Auth::user()->isStaff())
                    <a href="{{ route('mis.entradas') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('mis.entradas') ? 'bg-white/10 text-white shadow-inner' : 'text-primary-100 hover:bg-white/5 hover:text-white' }} transition-all">Mis Entradas</a>
                @endif
            @endauth
            
            <!-- Dark Mode Toggle Mobile -->
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
                    class="w-full text-left px-3 py-3 rounded-xl text-base font-bold text-primary-100 hover:bg-white/5 hover:text-white flex items-center gap-3 transition-all border border-white/5 bg-white/5 mt-2">
                <div class="p-1.5 rounded-lg bg-black/20">
                    <svg x-show="darkMode" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <svg x-show="!darkMode" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </div>
                <span x-text="darkMode ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"></span>
            </button>
        </div>
        
        <div class="pt-4 pb-4 border-t border-white/10 bg-black/10 backdrop-contrast-75">
            @auth
                <div class="flex items-center px-6 mb-4">
                    <div class="flex-shrink-0">
                        <img class="h-12 w-12 rounded-full border-2 border-white/20 shadow-lg" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=6366f1&background=eef2ff" alt="{{ Auth::user()->name }}" />
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-black text-white tracking-wide">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-primary-200">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-2 space-y-1 px-4">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium text-primary-100 hover:text-white hover:bg-white/10 transition">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2.5 rounded-lg text-base font-bold text-red-300 hover:text-white hover:bg-red-500/80 transition">Cerrar Sesión</button>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-3 px-6 pb-4">
                    <a href="{{ route('login') }}" class="block text-center w-full py-3 text-white border border-white/20 rounded-xl font-bold hover:bg-white/10 transition backdrop-blur-sm">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="block text-center w-full py-3 bg-blue-600/90 hover:bg-blue-500 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition backdrop-blur-sm">Registrarse</a>
                </div>
            @endauth
        </div>
    </div>
</nav>