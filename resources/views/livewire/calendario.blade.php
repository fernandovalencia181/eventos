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
                        
                        // Obtener eventos para este día
                        $dateKey = $currentDayDate->format('Y-m-d');
                        $dayEvents = $eventsByDate[$dateKey] ?? [];
                        $count = count($dayEvents);
                    @endphp

                    <div class="bg-white min-h-[120px] p-2 transition hover:bg-secondary-50 relative group border-r border-b border-secondary-100 flex flex-col cursor-pointer"
                         wire:click="toggleDate('{{ $dateKey }}')">
                        
                        <!-- Cabecera del día -->
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-sm font-bold {{ $isToday ? 'bg-primary-600 text-white w-7 h-7 flex items-center justify-center rounded-full' : 'text-secondary-700' }}">
                                {{ $day }}
                            </span>
                            @if($count > 0)
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-1.5 py-0.5 rounded-full">
                                    {{ $count }}
                                </span>
                            @endif
                        </div>

                        <!-- Renderizado de eventos y lógica +N -->
                        @if($count > 0)
                            <div class="flex-1 flex flex-col gap-1 overflow-hidden">
                                {{-- Primer evento visible siempre --}}
                                @php $firstEvent = $dayEvents[0]; @endphp
                                <div class="px-2 py-1 text-xs font-medium rounded-md truncate {{ $firstEvent['color'] ?? 'bg-indigo-50 text-indigo-700 border border-indigo-100' }}">
                                    {{ $firstEvent['title'] }}
                                </div>

                                {{-- "Más eventos" si hay más de 1 --}}
                                @if($count > 1)
                                    <div class="text-xs text-secondary-500 font-medium pl-1 mt-auto">
                                        +{{ $count - 1 }} más eventos...
                                    </div>
                                @endif
                            </div>

                            <!-- Popover / Desplegable al hacer clic -->
                            @if($openDate === $dateKey)
                                <div class="absolute z-50 left-0 top-full mt-1 w-64 bg-white rounded-xl shadow-xl border border-secondary-200 p-3 animate-fade-in-up origin-top-left" style="min-width: 200px;">
                                    <div class="flex justify-between items-center border-b border-secondary-100 pb-2 mb-2">
                                        <h4 class="text-sm font-bold text-secondary-900">
                                            {{ $currentDayDate->isoFormat('D MMM') }}
                                        </h4>
                                        <button wire:click.stop="toggleDate(null)" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-2 max-h-60 overflow-y-auto">
                                        @foreach($dayEvents as $event)
                                            <a href="{{ route('eventos.edit', $event['id']) }}" class="block p-2 rounded-lg hover:bg-secondary-50 transition border border-transparent hover:border-secondary-200 bg-gray-50">
                                                <div class="text-xs font-bold text-secondary-800 break-words">{{ $event['title'] }}</div>
                                                <div class="flex items-center text-[10px] text-secondary-500 mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ \Carbon\Carbon::parse($event['date'])->format('H:i') }}
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                @endfor
                
                <!-- Rellenar huecos al final si es necesario (opcional) -->
            </div>
        </div>

    </div>
</div>
