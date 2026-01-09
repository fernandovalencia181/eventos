<x-app-layout>
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-success text-white px-4 py-3 rounded-lg shadow-md flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                    ✕
                </button>
            </div>
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-secondary-900 tracking-tight">
                    Próximos <span class="text-primary-600">Eventos</span>
                </h1>
                <p class="mt-4 text-xl text-secondary-500">Reserva tu entrada para las mejores conferencias y graduaciones.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @forelse($eventos as $evento)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-secondary-100">
                        <div class="h-48 bg-gradient-to-r from-primary-500 to-primary-700 flex items-center justify-center">
                            <span class="text-white text-4xl font-bold opacity-30">IMG</span>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold bg-primary-50 text-primary-700 px-2 py-1 rounded-full">
                                    Aforo: {{ $evento->aforo_maximo }}
                                </span>
                                <span class="text-xs text-secondary-500">
                                    {{ \Carbon\Carbon::parse($evento->fecha)->format('d M, Y') }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-secondary-900 mb-2">{{ $evento->nombre }}</h3>
                            <p class="text-secondary-500 text-sm mb-4">📍 {{ $evento->lugar }}</p>
                            
                            <button class="w-full py-2.5 bg-secondary-900 text-white font-medium rounded-xl hover:bg-primary-600 transition-colors">
                                Obtener Ticket
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-10">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex justify-center">
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Aún no hay eventos programados. ¡Vuelve pronto!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>

