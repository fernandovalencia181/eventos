<x-app-layout>
    {{-- Alert Section --}}
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-4 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif
    
    <div class="py-12 relative overflow-hidden">
        {{-- Decorative Blob --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[300px] bg-blue-500/20 blur-[100px] rounded-full pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center mb-16">
                <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">
                    Próximos <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600">Eventos</span>
                </h1>
                <p class="max-w-2xl mx-auto text-xl text-gray-500 dark:text-gray-400 leading-relaxed">
                    Descubre y reserva tu lugar en las mejores conferencias, graduaciones y encuentros exclusivos de nuestra comunidad.
                </p>
            </div>
       
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @forelse($eventos as $evento)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 flex flex-col h-full transform hover:-translate-y-1">
                    <div class="relative overflow-hidden h-52">
                        @if($evento->imagen)
                            <img src="{{ Storage::url($evento->imagen) }}" alt="{{ $evento->nombre }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center">
                                <span class="text-white text-5xl font-black opacity-20 select-none">{{ substr($evento->nombre, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        {{-- Badge de Aforo --}}
                        <div class="absolute top-4 right-4 bg-black/50 backdrop-blur-md text-white text-xs font-bold px-3 py-1 rounded-full border border-white/20 shadow-sm">
                            {{ $evento->aforo_maximo }} Lugares
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-4">
                            <span class="inline-flex items-center text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 rounded-md mb-2 border border-blue-100 dark:border-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($evento->fecha)->translatedFormat('d M, Y') }}
                            </span>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $evento->nombre }}
                            </h3>
                        </div>
                        
                        <div class="flex items-start text-gray-500 dark:text-gray-400 text-sm mb-6 flex-1">
                            <svg class="w-5 h-5 mr-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="line-clamp-2">{{ $evento->lugar }}</span>
                        </div>
                        
                        <div class="mt-auto">
                            @if(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isStaff()))
                                 <div class="block text-center w-full py-3 bg-gray-50 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500 font-medium rounded-xl border border-dashed border-gray-200 dark:border-gray-600 cursor-default select-none transition-colors">
                                    Vista {{ ucfirst(Auth::user()->rol) }}
                                </div>
                            @else
                                <a href="{{ Auth::check() ? route('registro.create', $evento->id) : route('login') }}" class="block text-center w-full py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-cyan-500/40 hover:from-blue-700 hover:to-cyan-700 transition-all transform hover:-translate-y-0.5">
                                    Obtener Entrada
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <div class="flex flex-col items-center justify-center text-center py-20 bg-gray-50 dark:bg-gray-800/50 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-full mb-4">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 dark:text-white">No hay eventos próximos</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Estamos planificando nuevas experiencias. ¡Vuelve pronto!</p>
                    </div>
                </div>
            @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
