<div class="py-6 md:py-12"> 
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Mi Calendario</h1>
            
            <div class="flex items-center justify-between w-full md:w-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-1">
                <button wire:click="prevMonth" class="p-2 hover:bg-gray-50 rounded-xl text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <span class="text-base md:text-lg font-bold text-gray-800 capitalize w-40 text-center">
                    {{ $currentDate->locale('es')->isoFormat('MMMM YYYY') }}
                </span>
                <button wire:click="nextMonth" class="p-2 hover:bg-gray-50 rounded-xl text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 relative z-0">
            
            <div class="grid grid-cols-7 border-b border-gray-100">
                @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $day)
                    <div class="py-4 text-center text-xs md:text-sm font-semibold text-gray-400 uppercase tracking-wider">
                        {{ substr($day, 0, 1) }}<span class="hidden md:inline">{{ substr($day, 1) }}</span> 
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 auto-rows-fr bg-white rounded-b-2xl"> @for($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="min-h-[80px] md:min-h-[140px] p-1 md:p-2 bg-gray-50/30"></div>
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

                    <div class="relative min-h-[80px] md:min-h-[140px] group border-t border-r border-gray-50 transition-all hover:bg-gray-50 cursor-pointer flex flex-col items-center md:items-stretch {{ $isSelected ? 'z-50' : 'z-0' }}"
                         wire:click="toggleDate('{{ $dateKey }}')">
                        
                        <div class="mt-2 mx-auto md:mx-0 md:ml-2 w-8 h-8 flex items-center justify-center rounded-full text-sm font-medium transition-all
                            {{ $isToday 
                                ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' 
                                : ($isSelected ? 'bg-gray-900 text-white' : 'text-gray-700 group-hover:bg-white group-hover:shadow-sm') 
                            }}">
                            {{ $day }}
                        </div>

                        <div class="flex-1 w-full px-1 md:px-2 py-1 flex flex-col items-center md:items-stretch">
                            
                            @if($count > 0)
                                <div class="md:hidden mt-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-[#34C759] shadow-sm"></div>
                                </div>
                            @endif

                            <div class="hidden md:flex flex-col gap-1 mt-1">
                                @foreach(array_slice($dayEvents, 0, 3) as $event)
                                    <div class="px-2 py-0.5 text-xs font-medium rounded md:truncate {{ $event['color'] ?? 'bg-indigo-50 text-indigo-700' }} border-l-2 border-indigo-500 bg-opacity-50">
                                        {{ $event['title'] }}
                                    </div>
                                @endforeach
                                
                                @if($count > 3)
                                    <div class="text-xs text-gray-400 font-medium pl-1">
                                        +{{ $count - 3 }} más
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($openDate === $dateKey)
                            
                            <div class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/50 backdrop-blur-[2px] p-4 
                                        md:absolute md:inset-auto md:bg-transparent md:p-0 md:backdrop-blur-none md:top-full md:left-1/2 md:transform md:-translate-x-1/2 md:mt-2"
                                wire:click.self="toggleDate(null)"> <div class="bg-white rounded-xl shadow-2xl w-full max-w-xs md:w-72 md:shadow-xl border border-gray-100 overflow-hidden animate-fade-in-up ring-1 ring-black/5">
                                    
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            {{ $currentDayDate->isoFormat('dddd D') }}
                                        </h4>
                                        
                                        <button wire:click.stop="toggleDate(null)" class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full p-1 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <div class="p-2 space-y-1 max-h-[300px] overflow-y-auto custom-scrollbar bg-white">
                                        @forelse($dayEvents as $event)
                                            <a href="{{ route('eventos.edit', $event['id']) }}" class="flex flex-col p-3 rounded-lg hover:bg-gray-50 transition border border-transparent hover:border-indigo-100 group">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-indigo-700 transition-colors">{{ $event['title'] }}</span>
                                                    <span class="w-2 h-2 rounded-full {{ $event['color'] ?? 'bg-gray-300' }}"></span>
                                                </div>
                                                <span class="text-xs text-gray-400 flex items-center mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ \Carbon\Carbon::parse($event['date'])->format('H:i') }}
                                                </span>
                                            </a>
                                        @empty
                                            <div class="flex flex-col items-center justify-center py-6 text-center">
                                                <div class="bg-gray-50 rounded-full p-3 mb-2">
                                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                </div>
                                                <p class="text-xs text-gray-500 font-medium">No hay eventos</p>
                                            </div>
                                        @endforelse
                                    </div>

                                    <div class="p-2 border-t border-gray-50 bg-gray-50/50">
    <a href="{{ route('eventos.create') }}" 
       class="w-full flex items-center justify-center gap-2 text-sm text-indigo-600 hover:text-white hover:bg-indigo-600 font-medium py-2 px-4 rounded-lg transition-all duration-200">
        
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nuevo Evento
    </a>
</div>
                                </div>
                                
                                <div class="hidden md:block absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-white border-t border-l border-gray-100 rotate-45 z-10"></div>

                            </div>
                        @endif

                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>