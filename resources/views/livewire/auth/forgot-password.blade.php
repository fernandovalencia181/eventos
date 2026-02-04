<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-secondary-900">¿Olvidaste tu contraseña?</h2>
        <p class="text-sm text-secondary-500 mt-2">
            No te preocupes. Escribe tu correo y te enviaremos un enlace para recuperarla.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="block">
            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
            <input id="email" class="block mt-1 w-full rounded-lg border-indigo-200 bg-gray-50 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" type="email" name="email" :value="old('email')" required autofocus />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a href="{{ route('login') }}" class="text-sm text-secondary-600 hover:text-primary-600 underline mr-4">
                Volver al Login
            </a>
            
            <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-primary-700 transition transform hover:-translate-y-0.5">
                Enviar Enlace
            </button>
        </div>
    </form>
</x-guest-layout>