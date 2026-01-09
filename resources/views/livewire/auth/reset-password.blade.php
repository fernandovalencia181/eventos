<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-secondary-900">Nueva Contraseña</h2>
        <p class="text-sm text-secondary-500 mt-2">Elige una contraseña segura</p>
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

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="block">
            <label for="email" class="block text-sm font-medium text-secondary-700">Correo Electrónico</label>
            <input id="email" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" type="email" name="email" :value="old('email', $request->email)" required autofocus />
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-secondary-700">Nueva Contraseña</label>
            <input id="password" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" type="password" name="password" required autocomplete="new-password" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-secondary-700">Confirmar Contraseña</label>
            <input id="password_confirmation" class="block mt-1 w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <button type="submit" class="bg-secondary-900 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-secondary-800 transition transform hover:-translate-y-0.5">
                Guardar Nueva Contraseña
            </button>
        </div>
    </form>
</x-guest-layout>