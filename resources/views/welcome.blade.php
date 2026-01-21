
<x-app-layout>
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-md flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-secondary-600 hover:text-primary-600 font-medium">Login</a>
                    <a href="#" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-500 transition">Registrarse</a>
                </div>
            </div>
        </div>
    @endif
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-secondary-900 dark:text-white tracking-tight">
                    Próximos <span class="text-primary-600 dark:text-primary-400">Eventos</span>
                </h1>
                <p class="mt-4 text-xl text-secondary-500 dark:text-gray-400">Reserva tu entrada para las mejores conferencias y graduaciones.</p>
            </div>

       
       
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @forelse($eventos as $evento)
                <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-secondary-100 dark:border-primary-800">
                    @if($evento->imagen)
                        <img src="{{ Storage::url($evento->imagen) }}" alt="{{ $evento->nombre }}" class="h-48 w-full object-cover">
                    @else
                        <div class="h-48 bg-gradient-to-r from-primary-500 to-primary-700 flex items-center justify-center">
                            <span class="text-white text-4xl font-bold opacity-30">{{ substr($evento->nombre, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold bg-primary-50 dark:bg-primary-800 text-primary-700 dark:text-primary-300 px-2 py-1 rounded-full">
                                Aforo: {{ $evento->aforo_maximo }}
                            </span>
                            <span class="text-xs text-secondary-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($evento->fecha)->format('d M, Y') }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-secondary-900 dark:text-white mb-2">{{ $evento->nombre }}</h3>
                        <p class="text-secondary-500 dark:text-gray-400 text-sm mb-4">📍 {{ $evento->lugar }}</p>
                        
                        {{-- FLUJO DE REGISTRO PÚBLICO (SIN LOGIN REQUERIDO PARA ESCUELAS) --}}
                        <a href="{{ route('registro.create', $evento->id) }}" class="block text-center w-full py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-500 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Obtener Entrada
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-10">
                    <p class="text-secondary-500 dark:text-gray-400 text-lg">Aún no hay eventos programados.</p>
                </div>
            @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
