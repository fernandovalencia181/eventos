<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-secondary-900">Área Segura</h2>
        <p class="text-sm text-secondary-500 mt-2 text-justify">
            Estás intentando acceder a una zona protegida. Por favor, confirma tu contraseña para continuar.
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

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input id="password" class="block mt-1 w-full rounded-lg border-indigo-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" type="password" name="password" required autocomplete="current-password" autofocus />
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-primary-700 transition transform hover:-translate-y-0.5">
                Confirmar
            </button>
        </div>
    </form>
</x-guest-layout>