<x-app-layout>
    <!-- Información del Evento -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-secondary-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold mb-2 text-secondary-900">{{ $evento->nombre }}</h2>
                        <div class="flex items-center gap-4 text-lg text-secondary-600">
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $evento->fecha->format('d/m/Y H:i') }}
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $evento->lugar }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right flex flex-col items-end">
                        <span class="text-sm text-secondary-500">Panel Staff</span>
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pestañas de Navegación -->
            <div class="bg-white rounded-2xl shadow-lg p-2 mb-8 border border-secondary-100">
                <div class="flex gap-2 overflow-x-auto">
                    <a 
                        href="{{ route('staff.evento.validacion', $evento->id) }}" 
                        class="flex-1 px-6 py-3 text-center rounded-xl font-medium transition-all flex items-center justify-center gap-2
                            {{ request()->routeIs('staff.evento.validacion') 
                                ? 'bg-primary-600 text-white shadow-md' 
                                : 'bg-secondary-50 text-secondary-700 hover:bg-secondary-100' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Check-in
                    </a>
                    <a 
                        href="{{ route('staff.evento.aforo', $evento->id) }}" 
                        class="flex-1 px-6 py-3 text-center rounded-xl font-medium transition-all flex items-center justify-center gap-2
                            {{ request()->routeIs('staff.evento.aforo') 
                                ? 'bg-primary-600 text-white shadow-md' 
                                : 'bg-secondary-50 text-secondary-700 hover:bg-secondary-100' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Aforo
                    </a>
                    <a 
                        href="{{ route('staff.evento.incidencias', $evento->id) }}" 
                        class="flex-1 px-6 py-3 text-center rounded-xl font-medium transition-all flex items-center justify-center gap-2
                            {{ request()->routeIs('staff.evento.incidencias') 
                                ? 'bg-primary-600 text-white shadow-md' 
                                : 'bg-secondary-50 text-secondary-700 hover:bg-secondary-100' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Incidencias
                    </a>
                    <a 
                        href="{{ route('staff.evento.invitados', $evento->id) }}" 
                        class="flex-1 px-6 py-3 text-center rounded-xl font-medium transition-all flex items-center justify-center gap-2
                            {{ request()->routeIs('staff.evento.invitados') 
                                ? 'bg-primary-600 text-white shadow-md' 
                                : 'bg-secondary-50 text-secondary-700 hover:bg-secondary-100' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Invitados
                    </a>
                </div>
            </div>

            <!-- Contenido Principal -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</x-app-layout>
