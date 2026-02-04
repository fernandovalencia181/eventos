<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent">Mis Entradas</h1>
            <span class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-4 py-1.5 rounded-full text-sm font-bold shadow-sm border border-indigo-200 dark:border-indigo-800">
                {{ $registrations->count() }}
            </span>
        </div>

        @if($registrations->isEmpty())
            <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-xl p-12 text-center border border-secondary-200 dark:border-primary-800">
                <div class="flex justify-center mb-4">
                     <div class="p-3 bg-secondary-100 dark:bg-primary-800 rounded-full">
                        <svg class="w-10 h-10 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                     </div>
                </div>
                <h3 class="text-secondary-500 dark:text-secondary-400 text-lg font-medium">No tienes entradas registradas aún.</h3>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($registrations as $registration)
                    <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 border border-secondary-200 dark:border-primary-800 group transform hover:-translate-y-1">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5 relative overflow-hidden">
                             <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                            <h2 class="text-xl font-bold text-white truncate relative z-10" title="{{ $registration->event->nombre }}">
                                {{ $registration->event->nombre }}
                            </h2>
                        </div>

                        <div class="p-6">
                            <div class="mb-4 flex items-center text-secondary-600 dark:text-secondary-300 text-sm font-medium">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($registration->event->fecha)->translatedFormat('d M Y - h:i A') }}
                            </div>

                            <div class="mb-6 p-4 bg-secondary-50 dark:bg-primary-800 rounded-xl border border-secondary-100 dark:border-primary-700">
                                <p class="text-xs font-bold text-secondary-400 uppercase tracking-wider mb-1">Titular</p>
                                <p class="text-secondary-900 dark:text-white font-bold text-lg">{{ $registration->name }}</p>
                                @if($registration->guests->count() > 0)
                                    <div class="flex items-center mt-2 text-purple-600 dark:text-purple-400 text-sm font-semibold">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        + {{ $registration->guests->count() }} acompañantes
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('registro.download', $registration->id) }}" class="flex items-center justify-center w-full bg-white dark:bg-primary-800 hover:bg-secondary-50 dark:hover:bg-primary-700 text-indigo-600 dark:text-indigo-400 font-bold py-3 px-4 border border-indigo-200 dark:border-primary-600 rounded-xl shadow-sm transition-all duration-200 hover:shadow-md group-hover:border-indigo-300">
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