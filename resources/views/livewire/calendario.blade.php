<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-primary-900">Mi Calendario</h1>
            
            <div class="flex items-center space-x-4 bg-white rounded-lg shadow p-1">
                <button wire:click="prevMonth" class="p-2 hover:bg-secondary-100 rounded-md text-secondary-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <span class="text-lg font-bold text-secondary-800 w-32 text-center">
                    {{ $currentDate->locale('es')->isoFormat('MMMM YYYY') }}
                </span>
                <button wire:click="nextMonth" class="p-2 hover:bg-secondary-100 rounded-md text-secondary-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-secondary-200 overflow-hidden">
            <!-- Días de la semana -->
            <div class="grid grid-cols-7 bg-secondary-50 border-b border-secondary-200">
                @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $day)
                    <div class="py-3 text-center text-sm font-bold text-secondary-500 uppercase tracking-wide">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Grid del mes -->
            <div class="grid grid-cols-7 auto-rows-fr bg-secondary-200 gap-px">
                <!-- Espacios vacíos antes del primer día -->
                @for($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="bg-white min-h-[120px] p-2 opacity-50"></div>
                @endfor

                <!-- Días del mes -->
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDayDate = \Carbon\Carbon::createFromDate($year, $month, $day);
                        $isToday = $currentDayDate->isToday();
                        
                        // Filtrar eventos para este día
                        $dayEvents = $events->filter(function($event) use ($currentDayDate) {
                            return $event['date']->isSameDay($currentDayDate);
                        });
                    @endphp

                    <div class="bg-white min-h-[120px] p-2 transition hover:bg-secondary-50 relative group">
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-bold {{ $isToday ? 'bg-primary-600 text-white w-7 h-7 flex items-center justify-center rounded-full' : 'text-secondary-700' }}">
                                {{ $day }}
                            </span>
                        </div>

                        <div class="mt-2 space-y-1">
                            @foreach($dayEvents as $event)
                                <div class="text-xs p-1.5 rounded bg-primary-100 text-primary-700 border border-primary-200 truncate cursor-pointer hover:bg-primary-200" title="{{ $event['title'] }}">
                                    {{ \Carbon\Carbon::parse($event['date'])->format('H:i') }} {{ $event['title'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endfor
                
                <!-- Rellenar huecos al final si es necesario (opcional) -->
            </div>
        </div>

    </div>
</div>
