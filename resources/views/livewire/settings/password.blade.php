<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="_('Contraseña')" :subheading="_('Asegúrate de que tu cuenta esté protegida con una contraseña larga y segura.')">
        
        <form wire:submit="updatePassword" class="my-6 w-full space-y-6 bg-white p-6 rounded-xl shadow-sm border border-secondary-200">
            
            <div>
                <label for="current_password" class="block text-sm font-medium text-secondary-700">Contraseña Actual</label>
                <input wire:model="current_password" id="current_password" type="password" class="mt-1 block w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" autocomplete="current-password">
                @error('current_password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-secondary-700">Nueva Contraseña</label>
                <input wire:model="password" id="password" type="password" class="mt-1 block w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" autocomplete="new-password">
                @error('password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-secondary-700">Confirmar Nueva Contraseña</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" class="mt-1 block w-full rounded-lg border-secondary-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" autocomplete="new-password">
                @error('password_confirmation') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:bg-primary-700 transition">
                    {{ __('Guardar') }}
                </button>

                <x-action-message class="me-3 text-green-600 font-medium" on="password-updated">
                    {{ __('¡Contraseña Actualizada!') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>