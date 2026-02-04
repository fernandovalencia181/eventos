<footer class="bg-gradient-to-r from-primary-950/95 to-primary-800/90 backdrop-blur-md border-t border-primary-700/30 mt-auto shadow-inner-lg">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <!-- Columna 1: Info (Logo y Descripción) -->
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-primary-800 text-primary-200 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight">EventosU</span>
                </div>
                <p class="text-sm text-primary-300 leading-relaxed">
                    La plataforma líder para gestión y reserva de entradas para graduaciones y conferencias universitarias.
                </p>
            </div>

            <!-- Columna 2: Enlaces Rápidos -->
            <div>
                <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Plataforma</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-primary-300 hover:text-white transition text-sm">Inicio</a></li>
                    @auth
                        @if(!Auth::user()->isAdmin())
                            <li><a href="{{ route('mis.entradas') }}" class="text-primary-300 hover:text-white transition text-sm">Mis Entradas</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('login') }}" class="text-primary-300 hover:text-white transition text-sm">Iniciar Sesión</a></li>
                        <li><a href="{{ route('register') }}" class="text-primary-300 hover:text-white transition text-sm">Registrarse</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Columna 3: Soporte -->
            <div>
                <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Soporte</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-primary-300 hover:text-white transition text-sm">Centro de Ayuda</a></li>
                    <li><a href="#" class="text-primary-300 hover:text-white transition text-sm">Términos y Condiciones</a></li>
                    <li><a href="#" class="text-primary-300 hover:text-white transition text-sm">Política de Privacidad</a></li>
                </ul>
            </div>

            <!-- Columna 4: Redes Sociales -->
            <div>
                <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Síguenos</h3>
                <div class="flex space-x-4">
                    <a href="https://www.linkedin.com/company/la-salle-mollerussa/posts/?feedView=all" target="_blank" class="text-primary-400 hover:text-white transition">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 21.227.792 22 1.771 22h20.451C23.2 22 24 21.227 24 20.542V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>

                    <a href="https://www.youtube.com/@lasallemollerussa" target="_blank" class="text-primary-400 hover:text-white transition">
                        <span class="sr-only">YouTube</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/sallemollerussa/" target="_blank" class="text-primary-400 hover:text-white transition">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                            <circle cx="17.5" cy="6.5" r="1.5" stroke="none" fill="currentColor"></circle>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-primary-800 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-primary-400">
                &copy; {{ date('Y') }} EventosU. Todos los derechos reservados.
            </p>
            <div class="flex items-center mt-4 md:mt-0">
                <img src="{{ asset('images/logo-lasalle.jpg') }}" alt="La Salle Mollerussa" class="h-16 object-contain rounded-2xl">
            </div>
        </div>
    </div>
</footer>