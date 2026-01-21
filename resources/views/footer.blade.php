<footer class="bg-primary-900 border-t border-primary-800 mt-auto">
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
                    <a href="#" class="text-primary-400 hover:text-white transition">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="#" class="text-primary-400 hover:text-white transition">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                    </a>
                    <a href="#" class="text-primary-400 hover:text-white transition">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.409-.06 3.809-.06h.63zm1.673 5.377a6.716 6.716 0 01-6.72 6.712 6.716 6.716 0 01-6.72-6.712 6.716 6.716 0 016.72-6.712 6.716 6.716 0 016.72 6.712zM5.33 6.94a2.203 2.203 0 11-3.111-2.213 2.203 2.203 0 013.111 2.213z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-primary-800 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-primary-400">
                &copy; {{ date('Y') }} EventosU. Todos los derechos reservados.
            </p>
            <div class="flex space-x-4 mt-4 md:mt-0">
                <span class="text-xs text-primary-500">LA SALLE MOLLERUSSA</span>
            </div>
        </div>
    </div>
</footer>