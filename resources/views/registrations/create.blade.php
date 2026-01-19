<x-registration-layout>
    <x-validation-errors class="mb-4" />

    <form method="POST" action="{{ route('registro.store', $evento->id) }}" x-data="{ guests: [] }">
        @csrf

        <h2 class="text-2xl font-bold text-white mb-6 text-center">Registro para {{ $evento->nombre }}</h2>

        <!-- Titular -->
        <div class="mb-4">
            <x-label for="name" value="{{ __('Nombre Completo') }}" class="text-gray-300" />
            <x-input id="name" class="block mt-1 w-full bg-primary-800 border-primary-600 text-white placeholder-gray-400 focus:border-primary-500 focus:ring-primary-500" type="text" name="name" :value="old('name')" required autofocus />
        </div>

            <div class="mb-4">
                <x-label for="email" value="{{ __('Email') }}" class="text-gray-300" />
                <x-input id="email" class="block mt-1 w-full bg-primary-800 border-primary-600 text-white placeholder-gray-400 focus:border-primary-500 focus:ring-primary-500" type="email" name="email" :value="old('email')" required />
                <p class="text-xs text-gray-400 mt-1">Este email será tu identificador único.</p>
            </div>

            <!-- Selector de Estudios -->
            <div class="mb-4">
                <label for="estudios" class="block text-sm font-medium text-gray-300 mb-2">Selecciona tu curso/ciclo actual</label>
                <select id="estudios" name="estudios" required class="w-full bg-primary-800 border border-primary-600 text-white rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500">
                    <option value="" disabled selected>-- Selecciona una opción --</option>
                    
                    <optgroup label="ESO">
                      <option value="1eso">1º ESO</option>
                      <option value="2eso">2º ESO</option>
                      <option value="3eso">3º ESO</option>
                      <option value="4eso">4º ESO</option>
                    </optgroup>
                    
                    <optgroup label="Bachillerato">
                      <option value="1bach">1º Bachillerato (Todas las modalidades)</option>
                      <option value="2bach">2º Bachillerato (Todas las modalidades)</option>
                    </optgroup>
                    
                    <optgroup label="CFGM - Grado Medio">
                      <option value="gm-gestion">Gestión Administrativa</option>
                      <option value="gm-comercio">Actividades Comerciales</option>
                      <option value="gm-smr">Sistemas Microinformáticos y Redes (SMR)</option>
                      <option value="gm-teleco">Instalaciones de Telecomunicaciones</option>
                      <option value="gm-electrica">Instalaciones Eléctricas y Automáticas</option>
                      <option value="gm-video">Vídeo Disc-jockey y Sonido</option>
                      <option value="gm-vehiculos">Electromecánica de Vehículos</option>
                      <option value="gm-emergencias">Emergencias Sanitarias</option>
                    </optgroup>
                    
                    <optgroup label="CFGS - Grado Superior">
                      <option value="gs-admin">Administración y Finanzas</option>
                      <option value="gs-asir">Admin. de Sistemas Informáticos en Red (ASIR)</option>
                      <option value="gs-dam">Desarrollo de Aplicaciones Multiplataforma (DAM)</option>
                      <option value="gs-marketing">Marketing y Publicidad</option>
                      <option value="gs-robotica">Automatización y Robótica Industrial</option>
                      <option value="gs-automocion">Automoción</option>
                    </optgroup>

                    <optgroup label="PFI (Programas de Formación e Inserción)">
                      <option value="pfi-informatica">Auxiliar de Informática</option>
                      <option value="pfi-mecanica">Auxiliar de Mecánica</option>
                    </optgroup>
                </select>
            </div>

            <!-- Acompañantes -->
            <div class="mt-6 border-t border-primary-700 pt-4">
                <h3 class="text-lg font-medium text-white mb-2">Acompañantes</h3>
                <template x-for="(guest, index) in guests" :key="index">
                    <div class="mb-3 flex gap-2">
                        <input type="text" :name="'guests['+index+'][name]'" placeholder="Nombre del Acompañante" required class="flex-1 bg-primary-800 border border-primary-600 text-white rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500">
                        <button type="button" @click="guests.splice(index, 1)" class="text-red-400 hover:text-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                <button type="button" @click="if(guests.length < 3) guests.push({name: ''})" x-show="guests.length < 3" class="mt-2 text-sm text-primary-400 hover:text-primary-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Añadir Acompañante
                </button>
                <p class="text-xs text-gray-500 mt-1">Máximo 3 acompañantes.</p>
            </div>

            <div class="flex items-center justify-end mt-8">
                <x-button class="ml-4 bg-primary-600 hover:bg-primary-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg transform transition hover:scale-105">
                    {{ __('Generar Entrada') }}
                </x-button>
            </div>
        </form>
</x-registration-layout>
