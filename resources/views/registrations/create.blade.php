<x-registration-layout>
    <x-validation-errors class="mb-4" />

    {{-- LÓGICA DE AFORO (Calculado antes de renderizar el formulario) --}}
    @php
        // Obtenemos ocupación real usando el helper del modelo
        $ocupados = $evento->ocupacion; 
        $disponibles = $evento->lugares_disponibles;
        $capacidad = $evento->aforo_maximo;
        
        // Evitar división por cero si aforo es 0 (raro pero posible)
        $porcentaje = $capacidad > 0 ? ($ocupados / $capacidad) * 100 : 100;

        // El máximo de invitados que permitimos añadir es el mínimo entre:
        // 1. El límite configurado en el evento (max_guests)
        // 2. Los espacios disponibles MENOS 1 (el titular que se está registrando)
        $maxGuestsConfig = $evento->max_guests ?? 0;
        $maxInvitadosPosibles = max(0, min($maxGuestsConfig, $disponibles - 1));
    @endphp

    <form method="POST" action="{{ route('registro.store', $evento->id) }}" 
        x-data="{ 
            guests: [], 
            limit: {{ $maxInvitadosPosibles }} 
        }">
        @csrf

        <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">Registro para {{ $evento->nombre }}</h2>

        <!-- VISUALIZADOR DE AFORO -->
        <div class="mb-6 mx-auto w-full bg-gray-50 rounded-lg p-4 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-between items-end mb-1">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado del aforo</span>
                <span class="text-sm font-bold {{ $disponibles < 10 ? 'text-red-600' : 'text-blue-600' }}">
                    {{ $disponibles }} lugares disponibles
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-600">
                <div class="h-2.5 rounded-full {{ $disponibles < 10 ? 'bg-red-500' : 'bg-blue-600' }}" style="width: {{ $porcentaje }}%"></div>
            </div>
            <div class="mt-2 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>Total: {{ $capacidad }} personas</span>
                <span>Ocupado: {{ number_format($porcentaje, 0) }}%</span>
            </div>
        </div>

        <!-- Titular -->
        <div class="mb-4">
            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nombre Completo') }}</label>
            <input id="name" class="block mt-1 w-full bg-white border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required autofocus {{ auth()->check() ? 'readonly' : '' }} />
        </div>

        <div class="mb-4">
            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
            <input id="email" class="block mt-1 w-full bg-white border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required {{ auth()->check() ? 'readonly' : '' }} />
            <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">Este email será tu identificador único.</p>
        </div>

        <!-- Selector de Estudios -->
        <div class="mb-4">
            <label for="estudios" class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">Selecciona tu curso/ciclo actual</label>
            <select id="estudios" name="estudios" required class="w-full bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled selected>-- Selecciona una opción --</option>
                
                <optgroup label="CFGM - Grado Medio">
                    <option value="CFGM Vídeo Discjòquei i so">CFGM Vídeo Discjòquei i so</option>
                    <option value="CFGM Gestió Administrativa">CFGM Gestió Administrativa</option>
                    <option value="CFGM Instal·lacions Elèctriques i Automàtiques">CFGM Instal·lacions Elèctriques i Automàtiques</option>
                    <option value="CFGM Electromecànica de Vehicles Automòbils">CFGM Electromecànica de Vehicles Automòbils</option>
                    <option value="CFGM Emergències Sanitàries">CFGM Emergències Sanitàries</option>
                    <option value="CFGM Instal·lacions de Telecomunicacions">CFGM Instal·lacions de Telecomunicacions</option>
                </optgroup>
                
                <optgroup label="CFGS - Grado Superior">
                    <option value="CFGS Automoció">CFGS Automoció</option>
                    <option value="CFGS Administració i Finances">CFGS Administració i Finances</option>
                    <option value="CFGS Comerç Internacional">CFGS Comerç Internacional</option>
                    <option value="CFGS Desenvolupament d'Aplicacions Multiplataforma">CFGS Desenvolupament d'Aplicacions Multiplataforma</option>
                    <option value="CFGS Prevenció de Riscos Professionals">CFGS Prevenció de Riscos Professionals</option>
                    <option value="CFGS Automatització i Robòtica Industrial">CFGS Automatització i Robòtica Industrial</option>
                </optgroup>

            </select>
        </div>

            <div class="mt-4 space-y-4">
                <template x-for="(guest, index) in guests" :key="index">
                    <div class="p-4 bg-gray-50 border border-secondary-200 rounded-xl relative hover:shadow-md transition dark:bg-primary-800 dark:border-primary-700">
                         <button type="button" @click="guests.splice(index, 1)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 p-1.5 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-secondary-700 dark:text-secondary-300 mb-1">Nombre Completo</label>
                                <input type="text" x-model="guest.name" :name="'guests['+index+'][name]'" placeholder="Ej: Juan Pérez" required 
                                    class="block w-full rounded-lg border-secondary-300 dark:border-primary-600 dark:bg-primary-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-secondary-700 dark:text-secondary-300 mb-1">Teléfono (Opcional)</label>
                                <input type="text" x-model="guest.phone" :name="'guests['+index+'][phone]'" placeholder="+34 600..." 
                                    class="block w-full rounded-lg border-secondary-300 dark:border-primary-600 dark:bg-primary-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <button type="button" 
                    @click="if(guests.length < limit) guests.push({name: '', phone: ''})" 
                    x-show="guests.length < limit"
                    class="flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition py-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Añadir Acompañante
                </button>
                
                <span class="text-xs font-medium px-3 py-1 bg-secondary-100 dark:bg-primary-800 rounded-full text-secondary-600 dark:text-secondary-400">
                    <span x-text="guests.length"></span> / <span x-text="limit"></span> ocupados
                    <span x-show="limit < {{ $maxGuestsConfig }}" class="ml-1 text-orange-600 dark:text-orange-400 font-bold">(Restringido por aforo)</span>
                </span>
            </div>
            
            @if($maxGuestsConfig == 0)
                <p class="text-sm text-secondary-500 dark:text-secondary-400 italic mt-2 text-center border top-dashed border-secondary-200 dark:border-primary-700 pt-2">
                    Este evento no permite acompañantes.
                </p>
            @endif
        </div>

        <div class="mt-8 flex justify-end pt-6 border-t border-secondary-200 dark:border-primary-700">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-bold text-white uppercase tracking-widest hover:from-blue-700 hover:to-indigo-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg transform hover:scale-[1.02]">
                {{ __('Confirmar Registro') }}
                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</x-registration-layout>
