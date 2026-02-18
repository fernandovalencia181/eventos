<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg text-center">
            
            <div class="mb-6 flex justify-center">
                <svg class="h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                Acceso Bloqueado
            </h2>

            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Por motivos de seguridad, este pase o entrada digital ya ha sido vinculado a otro dispositivo o navegador.
            </p>

            <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    Solo puedes abrir tu entrada en el dispositivo donde la activaste por primera vez.
                </p>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-500 mb-8">
                Si crees que esto es un error, por favor contacta con el soporte del evento o acude a la entrada para verificar tu identidad.
            </p>

            <a href="/" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Volver al Inicio
            </a>
        </div>
    </div>
</x-guest-layout>
