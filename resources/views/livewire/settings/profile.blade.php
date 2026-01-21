<section class="w-full">

    <x-settings.layout :heading="_('Perfil')" :subheading="_('Actualiza tu nombre y correo electrónico')">
        
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6 bg-white p-6 rounded-xl shadow-sm border border-secondary-200">
            
            <div>
                <label for="name" class="block text-sm font-medium text-secondary-700">Nombre</label>
                <input wire:model="name" id="name" type="text" class="mt-1 block w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required autofocus autocomplete="name">
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-secondary-700">Correo Electrónico</label>
                <input wire:model="email" id="email" type="email" class="mt-1 block w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required autocomplete="email">
                @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-sm text-yellow-800">
                            {{ __('Tu correo no ha sido verificado.') }}

                            <button wire:click.prevent="resendVerificationNotification" class="underline text-sm text-yellow-900 hover:text-primary-600 cursor-pointer">
                                {{ __('Haz clic aquí para reenviar el enlace.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:bg-primary-700 transition">
                    {{ __('Guardar Cambios') }}
                </button>

                <x-action-message class="me-3 text-green-600 font-medium" on="profile-updated">
                    {{ __('¡Guardado!') }}
                </x-action-message>
            </div>
        </form>

        <div class="mt-10">
            <livewire:settings.delete-user-form />
        </div>
        
    </x-settings.layout>
</section>