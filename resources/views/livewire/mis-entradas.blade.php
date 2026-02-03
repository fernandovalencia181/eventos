<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mis Entradas</h1>
            <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-bold">
                {{ $registrations->count() }}
            </span>
        </div>

        @if($registrations->isEmpty())
            <div class="bg-white rounded-xl shadow p-12 text-center border border-gray-200">
                <h3 class="text-gray-500 text-lg">No tienes entradas registradas aún.</h3>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($registrations as $registration)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-indigo-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white truncate" title="{{ $registration->event->nombre }}">
                                {{ $registration->event->nombre }}
                            </h2>
                        </div>

                        <div class="p-6">
                            <div class="mb-4 flex items-center text-gray-600 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($registration->event->fecha)->translatedFormat('d M Y - h:i A') }}
                            </div>

                            <div class="mb-4">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Titular</p>
                                <p class="text-gray-800 font-medium">{{ $registration->name }}</p>
                                @if($registration->guests->count() > 0)
                                    <p class="text-xs text-indigo-600 mt-1">+ {{ $registration->guests->count() }} acompañantes</p>
                                @endif
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('registro.download', $registration->id) }}" class="flex items-center justify-center w-full bg-gray-50 hover:bg-gray-100 text-indigo-600 font-semibold py-2 px-4 border border-gray-200 rounded shadow-sm transition duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Descargar Entrada PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>