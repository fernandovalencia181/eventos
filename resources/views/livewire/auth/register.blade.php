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
            <label for="name" class="block text-sm font-medium text-secondary-700">Nombre Completo</label>
            <input id="name" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>

        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-secondary-700">Correo Electrónico</label>
            <input id="email" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
        </div>

        <div class="mt-4">
            <label for="phone" class="block text-sm font-medium text-secondary-700">Teléfono (Opcional)</label>
            <input id="phone" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" type="text" name="phone" :value="old('phone')" autocomplete="tel" />
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-secondary-700">Contraseña</label>
            <input id="password" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" type="password" name="password" required autocomplete="new-password" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-secondary-700">Confirmar Contraseña</label>
            <input id="password_confirmation" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mt-4">
                <label for="terms" class="flex items-center">
                    <input type="checkbox" name="terms" id="terms" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" required />
                    <div class="ms-2 text-sm text-secondary-600">
                        {!! __('Acepto los :terms_of_service y la :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-secondary-600 hover:text-secondary-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Términos').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-secondary-600 hover:text-secondary-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Política de Privacidad').'</a>',
                        ]) !!}
                    </div>
                </label>
            </div>
        @endif

        <div class="flex items-center justify-end mt-6">
            <a class="text-sm text-secondary-600 hover:text-primary-600 underline mr-4" href="{{ route('login') }}">
                ¿Ya estás registrado?
            </a>

            <button type="submit" class="bg-secondary-900 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-secondary-800 transition transform hover:-translate-y-0.5">
                Registrarse
            </button>
        </div>
    </form>
</x-guest-layout>