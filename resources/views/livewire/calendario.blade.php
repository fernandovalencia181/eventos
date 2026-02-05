<div class="py-6 md:py-12"> 
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        
        <!-- Header Section with Staff Style -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-6">
            <div class="flex items-center gap-4">
                <div class="relative w-14 h-14 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-white/20 rounded-2xl backdrop-blur-sm"></div>
                    <svg class="w-7 h-7 text-white relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">Calendario</h1>
                    <p class="text-secondary-600 dark:text-secondary-400 text-sm">Organización de eventos mensuales</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between w-full md:w-auto bg-white dark:bg-primary-900 rounded-xl shadow-md border border-secondary-200 dark:border-primary-800 p-1.5">
                <button wire:click="prevMonth" class="p-2 hover:bg-secondary-50 dark:hover:bg-primary-800 rounded-lg text-secondary-500 dark:text-secondary-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <span class="text-lg font-bold text-secondary-800 dark:text-white capitalize w-48 text-center">
                    {{ $currentDate->locale('es')->isoFormat('MMMM YYYY') }}
                </span>
                <button wire:click="nextMonth" class="p-2 hover:bg-secondary-50 dark:hover:bg-primary-800 rounded-lg text-secondary-500 dark:text-secondary-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-xl border border-secondary-200 dark:border-primary-800 relative z-0 overflow-hidden">
            
            <!-- Days Header -->
            <div class="grid grid-cols-7 border-b border-secondary-200 dark:border-primary-800 bg-secondary-50 dark:bg-primary-800/50">
                @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $day)
                    <div class="py-3 text-center text-xs md:text-sm font-bold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">
                        {{ substr($day, 0, 1) }}<span class="hidden md:inline">{{ substr($day, 1) }}</span> 
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 auto-rows-fr bg-white dark:bg-primary-900"> @for($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="min-h-[100px] md:min-h-[140px] p-1 md:p-2 bg-secondary-50/30 dark:bg-primary-900/50 border-r border-b border-secondary-100 dark:border-primary-800"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDayDate = \Carbon\Carbon::createFromDate($year, $month, $day);
                        $isToday = $currentDayDate->isToday();
                        $dateKey = $currentDayDate->format('Y-m-d');
                        $dayEvents = $eventsByDate[$dateKey] ?? [];
                        $count = count($dayEvents);
                        $isSelected = ($openDate === $dateKey);
                    @endphp

                    <div class="relative min-h-[100px] md:min-h-[140px] group border-b border-r border-secondary-100 dark:border-primary-800 transition-all hover:bg-secondary-50 dark:hover:bg-primary-800/30 cursor-pointer flex flex-col items-center md:items-stretch {{ $isSelected ? 'z-50' : 'z-0' }}"
                         wire:click="toggleDate('{{ $dateKey }}')">
                        
                        <div class="mt-2 mx-auto md:mx-0 md:ml-2 w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold transition-all
                            {{ $isToday 
                                ? 'bg-gradient-to-br from-cyan-400 to-blue-500 text-white shadow-lg shadow-cyan-500/30' 
                                : ($isSelected ? 'bg-gradient-to-br from-cyan-600 to-blue-700 text-white shadow-md transform scale-110 ring-2 ring-white dark:ring-primary-800' : 'text-secondary-700 dark:text-secondary-300 group-hover:bg-white dark:group-hover:bg-primary-700 group-hover:shadow-sm') 
                            }}">
                            {{ $day }}
                        </div>

                        <div class="flex-1 w-full px-1 md:px-2 py-1 flex flex-col items-center md:items-stretch">
                            
                            @if($count > 0)
                                <div class="md:hidden mt-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-cyan-500 shadow-sm"></div>
                                </div>
                            @endif

                            <div class="hidden md:flex flex-col gap-1 mt-1">
                                @foreach(array_slice($dayEvents, 0, 3) as $event)
                                    <div class="px-2 py-1 text-[10px] font-bold rounded-md border-l-2 truncate transition-all shadow-sm
                                        {{ $event['color'] ?? 'bg-cyan-100 text-cyan-800 border-cyan-500 dark:bg-cyan-900/40 dark:text-cyan-300 dark:border-cyan-400' }}">
                                        {{ $event['title'] }}
                                    </div>
                                @endforeach
                                
                                @if($count > 3)
                                    <div class="text-[10px] text-secondary-500 dark:text-secondary-500 font-bold pl-1">
                                        +{{ $count - 3 }} más
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($openDate === $dateKey)
                            <!-- Modal/Popup (Fixed Center Mode) -->
                            <div class="fixed inset-0 z-[100] flex items-center justify-center bg-secondary-900/60 backdrop-blur-sm p-4 cursor-default"
                                wire:click.self="toggleDate(null)">
                                
                                <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden animate-fade-in-up ring-1 ring-black/5 relative transform transition-all scale-100 dark:border dark:border-primary-700">
                                    
                                    <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-5 py-4 flex justify-between items-center text-white">
                                        <h4 class="text-lg font-bold flex items-center gap-2">
                                            <svg class="w-5 h-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            <span class="capitalize">{{ $currentDayDate->isoFormat('dddd D [de] MMMM') }}</span>
                                        </h4>
                                        
                                        <button wire:click.stop="toggleDate(null)" class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-1 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <div class="p-3 space-y-2 max-h-[60vh] overflow-y-auto custom-scrollbar bg-white dark:bg-primary-900">
                                        @forelse($dayEvents as $event)
                                            <a href="{{ route('eventos.edit', $event['id']) }}" class="flex flex-col p-4 rounded-xl hover:bg-cyan-50 dark:hover:bg-primary-800/50 transition border border-secondary-100 dark:border-primary-800 hover:border-cyan-200 dark:hover:border-cyan-800/50 group">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="font-bold text-secondary-800 dark:text-white group-hover:text-cyan-700 dark:group-hover:text-cyan-400 transition-colors">{{ $event['title'] }}</span>
                                                    <span class="w-2.5 h-2.5 rounded-full {{ $event['color'] ?? 'bg-secondary-300' }}"></span>
                                                </div>
                                                <div class="flex items-center text-sm text-secondary-500 dark:text-secondary-400">
                                                    <svg class="w-4 h-4 mr-1.5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ \Carbon\Carbon::parse($event['date'])->format('H:i') }} hs
                                                </div>
                                            </a>
                                        @empty
                                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                                <div class="bg-secondary-50 dark:bg-primary-800 rounded-full p-4 mb-3">
                                                    <svg class="w-8 h-8 text-secondary-300 dark:text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                </div>
                                                <p class="text-sm text-secondary-500 dark:text-secondary-400 font-medium">No hay eventos programados</p>
                                                <p class="text-xs text-secondary-400 dark:text-secondary-500 mt-1">Selecciona 'Nuevo Evento' para agregar uno.</p>
                                            </div>
                                        @endforelse
                                    </div>

                                    <div class="p-4 border-t border-secondary-100 dark:border-primary-800 bg-secondary-50 dark:bg-primary-800/50">
                                        <a href="{{ route('eventos.create') }}" 
                                        class="w-full flex items-center justify-center gap-2 text-base bg-gradient-to-r from-cyan-600 to-blue-600 text-white hover:from-cyan-700 hover:to-blue-700 font-bold py-3 px-4 rounded-xl shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Crear Nuevo Evento
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>