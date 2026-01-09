<x-guest-layout>
    <div x-data="{ recovery: false }">
        
        <div class="mb-6 text-center">
            <h2 class="text-xl font-bold text-secondary-900">Autenticación en dos pasos</h2>
            
            <p class="text-sm text-secondary-500 mt-2" x-show="!recovery">
                Confirma el acceso ingresando el código de tu aplicación autenticadora.
            </p>
            <p class="text-sm text-secondary-500 mt-2" x-show="recovery" style="display: none;">
                Ingresa uno de tus códigos de emergencia para acceder.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200">
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
                <label for="code" class="block text-sm font-medium text-secondary-700">Código de Autenticación</label>
                <input id="code" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-center tracking-widest text-lg" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
            </div>

            <div class="mt-4" x-show="recovery" style="display: none;">
                <label for="recovery_code" class="block text-sm font-medium text-secondary-700">Código de Recuperación</label>
                <input id="recovery_code" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="button" class="text-sm text-secondary-600 hover:text-primary-600 underline cursor-pointer mr-4"
                        x-on:click="
                            recovery = ! recovery;
                            $nextTick(() => { recovery ? $refs.recovery_code.focus() : $refs.code.focus() })
                        ">
                    <span x-show="!recovery">Usar código de recuperación</span>
                    <span x-show="recovery" style="display: none;">Usar código de autenticación</span>
                </button>

                <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-primary-700 transition">
                    Acceder
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>