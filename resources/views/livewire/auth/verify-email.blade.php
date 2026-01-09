<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-secondary-900">Verifica tu Correo</h2>
        <p class="text-sm text-secondary-500 mt-2 text-justify">
            ¡Gracias por registrarte! Antes de empezar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar?
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
            Se ha enviado un nuevo enlace de verificación a tu correo.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-primary-700 transition">
                Reenviar Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-secondary-600 hover:text-red-600 underline">
                Cerrar Sesión
            </button>
        </form>
    </div>
</x-guest-layout>