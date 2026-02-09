<section class="w-full">
    <x-settings.layout :heading="__('Contraseña')" :subheading="__('Asegúrate de que tu cuenta esté protegida con una contraseña larga y segura.')">
        
        <form wire:submit="updatePassword" class="space-y-6">
            
            <div>
                <label for="current_password" class="block text-sm font-medium text-secondary-700 dark:text-secondary-300">Contraseña Actual</label>
                <input wire:model="current_password" id="current_password" type="password" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-700 bg-white dark:bg-primary-800 text-secondary-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-200" autocomplete="current-password">
                @error('current_password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-secondary-700 dark:text-secondary-300">Nueva Contraseña</label>
                <input wire:model="password" id="password" type="password" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-700 bg-white dark:bg-primary-800 text-secondary-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-200" autocomplete="new-password">
                @error('password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-secondary-700 dark:text-secondary-300">Confirmar Nueva Contraseña</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-700 bg-white dark:bg-primary-800 text-secondary-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-200" autocomplete="new-password">
                @error('password_confirmation') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-secondary-200 dark:border-primary-800">
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transform hover:-translate-y-0.5 transition-all duration-200">
                    {{ __('Guardar') }}
                </button>

                <x-action-message class="me-3 text-green-500 font-bold" on="password-updated">
                    {{ __('¡Contraseña Actualizada!') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>