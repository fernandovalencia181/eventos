<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Cuenta</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Únete a EventosU y consigue tus tickets</p>
    </div>

    <x-validation-errors class="mb-4" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Botón de Google --}}
        <div class="mb-6">
            <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full px-4 py-2.5 border border-gray-300 dark:border-primary-600 rounded-xl shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-primary-800 hover:bg-gray-50 dark:hover:bg-primary-700 transition-colors">
                <img class="h-5 w-5 mr-3" src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google logo">
                Registrarse con Google
            </a>
        </div>

        <div class="relative mb-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-primary-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-primary-900 text-gray-500 dark:text-gray-400">O con correo</span>
            </div>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Completo</label>
            <input id="name" class="block mt-1 w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
            <input id="email" class="block mt-1 w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono (Opcional)</label>
            <input id="phone" class="block mt-1 w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel" />
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
            <input id="password" class="block mt-1 w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors" type="password" name="password" required autocomplete="new-password" />
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Contraseña</label>
            <input id="password_confirmation" class="block mt-1 w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mb-4">
                <label for="terms" class="flex items-start sm:items-center">
                    <input type="checkbox" name="terms" id="terms" class="mt-1 sm:mt-0 rounded border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-blue-600 shadow-sm focus:ring-blue-500" required />
                    <div class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                        {!! __('Acepto los :terms_of_service y la :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">'.__('Términos').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">'.__('Política de Privacidad').'</a>',
                        ]) !!}
                    </div>
                </label>
            </div>
        @endif

        <div class="flex flex-col gap-4 mt-8 sm:flex-row sm:items-center sm:justify-between">
            <a class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 underline text-center sm:text-left transition-colors order-2 sm:order-1" href="{{ route('login') }}">
                ¿Ya estás registrado?
            </a>

            <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:from-blue-700 hover:to-cyan-700 transition-all transform hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 order-1 sm:order-2">
                Registrarse
            </button>
        </div>
    </form>
</x-guest-layout>
