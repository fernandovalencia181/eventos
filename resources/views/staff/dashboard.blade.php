<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">
            📋 Panel Staff - Selecciona un Evento
        </h1>

        @if($eventos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventos as $evento)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        @if($evento->imagen)
                            <img 
                                src="{{ Storage::url($evento->imagen) }}" 
                                alt="{{ $evento->nombre }}"
                                class="w-full h-48 object-cover"
                            >
                        @else
                            <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <span class="text-6xl">🎫</span>
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $evento->nombre }}
                            </h3>
                            
                            <div class="space-y-2 mb-4 text-sm text-gray-600 dark:text-gray-400">
                                <p>📅 {{ $evento->fecha->format('d/m/Y H:i') }}</p>
                                <p>📍 {{ $evento->lugar }}</p>
                                <p>👥 Aforo: {{ $evento->aforo_maximo }}</p>
                            </div>

                            <a 
                                href="{{ route('staff.evento.validacion', $evento->id) }}"
                                class="block w-full px-4 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 font-medium"
                            >
                                🎫 Gestionar Evento
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
                <p class="text-gray-500 dark:text-gray-400 text-lg">
                    No hay eventos próximos disponibles
                </p>
            </div>
        @endif
    </div>
</x-app-layout>
