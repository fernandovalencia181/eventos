<x-registration-layout>
    <x-validation-errors class="mb-4" />

    <form method="POST" action="{{ route('registro.store', $evento->id) }}" x-data="{ guests: [] }">
        @csrf

        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Registro para {{ $evento->nombre }}</h2>

        <!-- Titular -->
        <div class="mb-4">
            <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Nombre Completo') }}</label>
            <input id="name" class="block mt-1 w-full bg-white border-gray-300 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="name" value="{{ old('name') }}" required autofocus />
        </div>

        <div class="mb-4">
            <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
            <input id="email" class="block mt-1 w-full bg-white border-gray-300 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="email" name="email" value="{{ old('email') }}" required />
            <p class="text-xs text-gray-500 mt-1">Este email será tu identificador único.</p>
        </div>

        <!-- Selector de Estudios -->
        <div class="mb-4">
            <label for="estudios" class="block text-sm font-medium text-gray-700 mb-2">Selecciona tu curso/ciclo actual</label>
            <select id="estudios" name="estudios" required class="w-full bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                <option value="" disabled selected>-- Selecciona una opción --</option>
                
                <optgroup label="CFGM - Grado Medio">
                    <option value="gm-gestion">CFGM Vídeo Discjòquei i so </option>
                    <option value="gm-comercio">CFGM Gestió Administrativa</option>
                    <option value="gm-smr">CFGM Instal·lacions Elèctriques i Automàtiques</option>
                    <option value="gm-teleco">CFGM Electromecànica de Vehicles Automòbils</option>
                    <option value="gm-electrica">CFGM Emergències Sanitàries </option>
                    <option value="gm-video">CFGM Instal·lacions de Telecomunicacions</option>
                </optgroup>
                
                <optgroup label="CFGS - Grado Superior">
                    <option value="gs-admin">CFGS Automoció</option>
                    <option value="gs-asir">CFGS Administració i Finances</option>
                    <option value="gs-dam">CFGS Desenvolupament d'Aplicacions Multiplataforma</option>
                    <option value="gs-marketing">CFGS Prevenció de Riscos Professionals</option>
                    <option value="gs-robotica">CFGS Automatització i Robòtica Industrial</option>
                </optgroup>

            </select>
        </div>

        <!-- Acompañantes -->
        <div class="mt-6 border-t border-gray-200 pt-4">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Acompañantes</h3>
            
            <template x-for="(guest, index) in guests" :key="index">
                <div class="mb-3 p-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm space-y-3 relative">
                     <button type="button" @click="guests.splice(index, 1)" class="absolute top-2 right-2 text-red-600 hover:text-red-800 p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" x-model="guest.name" :name="'guests['+index+'][name]'" placeholder="Nombre del Acompañante" required class="block w-full mt-1 bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono (WhatsApp)</label>
                        <input type="text" x-model="guest.phone" :name="'guests['+index+'][phone]'" placeholder="+34 600..." class="block w-full mt-1 bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                         <p class="text-xs text-gray-500 mt-1">Necesario para enviarle su entrada por WhatsApp.</p>
                    </div>
                </div>
            </template>

            <button type="button" @click="if(guests.length < 3) guests.push({name: '', phone: ''})" x-show="guests.length < 3" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 flex items-center font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Añadir Acompañante
            </button>
            <p class="text-xs text-gray-500 mt-1">Máximo 3 acompañantes.</p>
        </div>

        <div class="flex items-center justify-end mt-8">
            <button type="submit" class="inline-flex items-center ml-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transform transition hover:scale-105 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('Generar Entrada') }}
            </button>
        </div>
    </form>
</x-registration-layout>
