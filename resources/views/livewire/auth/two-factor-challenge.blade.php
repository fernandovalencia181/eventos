<x-guest-layout>
    <div x-data="{ recovery: false }">
        
        <div class="mb-6 text-center">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Autenticación en dos pasos</h2>
            
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2" x-show="!recovery">
                Confirma el acceso ingresando el código de tu aplicación autenticadora.
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2" x-show="recovery" style="display: none;">
                Ingresa uno de tus códigos de emergencia para acceder.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 p-3 rounded-lg border border-red-200 dark:border-red-800">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="mt-4" x-show="!recovery">
                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código de Autenticación</label>
                <input id="code" class="block mt-1 w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-center tracking-widest text-lg shadow-sm transition-colors" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
            </div>

            <div class="mt-4" x-show="recovery" style="display: none;">
                <label for="recovery_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código de Recuperación</label>
                <input id="recovery_code" class="block mt-1 w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 underline cursor-pointer mr-4 transition-colors"
                        x-on:click="
                            recovery = ! recovery;
                            $nextTick(() => { recovery ? $refs.recovery_code.focus() : $refs.code.focus() })
                        ">
                    <span x-show="!recovery">Usar código de recuperación</span>
                    <span x-show="recovery" style="display: none;">Usar código de autenticación</span>
                </button>

                <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:from-blue-700 hover:to-cyan-700 transition-all transform hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Acceder
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
