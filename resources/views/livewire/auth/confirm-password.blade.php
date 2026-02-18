<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Área Segura</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 text-justify">
            Estás intentando acceder a una zona protegida. Por favor, confirma tu contraseña para continuar.
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

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
            <input id="password" class="block mt-1 w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors" type="password" name="password" required autocomplete="current-password" autofocus />
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:from-blue-700 hover:to-cyan-700 transition-all transform hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Confirmar
            </button>
        </div>
    </form>
</x-guest-layout>
