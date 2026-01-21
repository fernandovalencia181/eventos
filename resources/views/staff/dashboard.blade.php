<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-secondary-900 tracking-tight">
                    📋 Panel <span class="text-primary-600">Staff</span>
                </h1>
                <p class="mt-4 text-xl text-secondary-500">Selecciona un evento para gestionar</p>
            </div>

            @if($eventos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($eventos as $evento)
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-secondary-100">
                            @if($evento->imagen)
                                <img 
                                    src="{{ Storage::url($evento->imagen) }}" 
                                    alt="{{ $evento->nombre }}"
                                    class="w-full h-48 object-cover"
                                >
                            @else
                                <div class="w-full h-48 bg-gradient-to-r from-primary-500 to-primary-700 flex items-center justify-center">
                                    <span class="text-white text-4xl font-bold opacity-30">{{ substr($evento->nombre, 0, 1) }}</span>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold bg-primary-50 text-primary-700 px-2 py-1 rounded-full">
                                        Aforo: {{ $evento->aforo_maximo }}
                                    </span>
                                    <span class="text-xs text-secondary-500">
                                        {{ $evento->fecha->format('d M, Y') }}
                                    </span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-secondary-900 mb-2">
                                    {{ $evento->nombre }}
                                </h3>
                                
                                <p class="text-secondary-500 text-sm mb-4 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $evento->lugar }}
                                </p>

                                <a 
                                    href="{{ route('staff.evento.validacion', $evento->id) }}"
                                    class="block w-full py-2.5 bg-secondary-900 text-white font-medium rounded-xl hover:bg-primary-600 transition-colors text-center flex items-center justify-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                    Gestionar Evento
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-secondary-100">
                    <p class="text-secondary-500 text-lg">
                        No hay eventos próximos disponibles
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
