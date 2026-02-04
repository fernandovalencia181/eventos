<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-secondary-900">Crear Cuenta</h2>
        <p class="text-sm text-secondary-500 mt-2">Únete a EventosU y consigue tus tickets</p>
    </div>

    <x-validation-errors class="mb-4" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Botón de Google --}}
        <div class="mb-4">
            <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google logo">
                Registrarse con Google
            </a>
        </div>

        <div class="relative mb-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">O con correo</span>
            </div>
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
            <input id="name" class="block mt-1 w-full rounded-lg border-indigo-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>

        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
            <input id="email" class="block mt-1 w-full rounded-lg border-indigo-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
        </div>

        <div class="mt-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono (Opcional)</label>
            <input id="phone" class="block mt-1 w-full rounded-lg border-indigo-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" type="text" name="phone" :value="old('phone')" autocomplete="tel" />
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input id="password" class="block mt-1 w-full rounded-lg border-indigo-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" type="password" name="password" required autocomplete="new-password" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
            <input id="password_confirmation" class="block mt-1 w-full rounded-lg border-indigo-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mt-4">
                <label for="terms" class="flex items-start sm:items-center">
                    <input type="checkbox" name="terms" id="terms" class="mt-1 sm:mt-0 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" required />
                    <div class="ms-2 text-sm text-gray-600">
                        {!! __('Acepto los :terms_of_service y la :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Términos').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Política de Privacidad').'</a>',
                        ]) !!}
                    </div>
                </label>
            </div>
        @endif

        <div class="flex flex-col gap-4 mt-6 sm:flex-row sm:items-center sm:justify-between">
            <a class="text-sm text-gray-500 hover:text-indigo-600 underline text-center sm:text-left transition-colors order-2 sm:order-1" href="{{ route('login') }}">
                ¿Ya estás registrado?
            </a>

            <button type="submit" class="w-full sm:w-auto bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:bg-black transition-all transform hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 order-1 sm:order-2">
                Registrarse
            </button>
        </div>
    </form>
</x-guest-layout>