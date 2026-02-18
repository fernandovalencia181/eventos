<section class="w-full">

    <x-settings.layout :heading="__('Perfil')" :subheading="__('Actualiza tu nombre y correo electrónico')">
        
        <form wire:submit="updateProfileInformation" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-secondary-700 dark:text-secondary-300">Nombre</label>
                    <input wire:model="name" id="name" type="text" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-700 bg-white dark:bg-primary-800 text-secondary-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-200" required autofocus autocomplete="name">
                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-secondary-700 dark:text-secondary-300">Correo Electrónico</label>
                    <input wire:model="email" id="email" type="email" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-700 bg-white dark:bg-primary-800 text-secondary-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-200" required autocomplete="email">
                    @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-secondary-700 dark:text-secondary-300">Teléfono (WhatsApp)</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input wire:model="phone" id="phone" type="text" class="block w-full pl-10 rounded-xl border-secondary-300 dark:border-primary-700 bg-white dark:bg-primary-800 text-secondary-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-200" placeholder="+549..." autocomplete="tel">
                </div>
                <p class="mt-2 text-xs text-secondary-500 dark:text-secondary-400">Ingresa tu número con código de país para recibir tus entradas y notificaciones.</p>
                @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-700">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                {{ __('Tu correo no ha sido verificado.') }}
                                <button wire:click.prevent="resendVerificationNotification" class="font-medium underline text-yellow-700 dark:text-yellow-300 hover:text-yellow-600 dark:hover:text-yellow-200 transition">
                                    {{ __('Haz clic aquí para reenviar el enlace.') }}
                                </button>
                            </p>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 ml-8 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
                        </p>
                    @endif
                </div>
            @endif

            <div class="flex items-center gap-4 pt-6 border-t border-secondary-200 dark:border-primary-800">
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transform hover:-translate-y-0.5 transition-all duration-200">
                    {{ __('Guardar Cambios') }}
                </button>

                <x-action-message class="me-3 text-green-500 font-bold" on="profile-updated">
                    {{ __('¡Guardado con éxito!') }}
                </x-action-message>
            </div>
        </form>

        <div class="mt-10 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Borrar Cuenta</h3>
            <p class="mt-1 text-sm text-gray-500 mb-4">
                Una vez borrada tu cuenta, todos sus recursos y datos se eliminarán permanentemente.
            </p>
            <livewire:settings.delete-user-form />
        </div>
    </x-settings.layout>
</section>