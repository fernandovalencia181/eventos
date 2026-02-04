<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-secondary-900">Bienvenido de nuevo</h2>
        <p class="text-sm text-secondary-500 mt-2">Ingresa tus credenciales para acceder</p>
    </div>

    <x-validation-errors class="mb-4" />

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Botón de Google --}}
        <div class="mb-4">
            <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google logo">
                Iniciar sesión con Google
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
            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
            <input id="email" class="block mt-1 w-full rounded-lg border-indigo-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input id="password" class="block mt-1 w-full rounded-lg border-indigo-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" type="password" name="password" required autocomplete="current-password" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" name="remember">
                <span class="ms-2 text-sm text-secondary-600">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs sm:text-sm text-gray-500 hover:text-indigo-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <div class="flex flex-col gap-4 mt-6 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-indigo-600 underline text-center sm:text-left transition-colors order-2 sm:order-1">
                ¿No tienes cuenta?
            </a>

            <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:bg-indigo-700 transition-all transform hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 order-1 sm:order-2">
                Iniciar Sesión
            </button>
        </div>
    </form>
</x-guest-layout>