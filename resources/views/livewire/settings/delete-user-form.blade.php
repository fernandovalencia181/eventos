<section class="mt-10 pt-10 border-t border-secondary-200 w-full">
    
    <div class="mb-6">
        <h3 class="text-lg font-bold text-red-600">{{ __('Borrar Cuenta') }}</h3>
        <p class="mt-1 text-sm text-secondary-500">
            {{ __('Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente.') }}
        </p>
    </div>

    <div class="bg-red-50 p-6 rounded-xl border border-red-100">
        <p class="text-sm text-red-800 mb-4">
            {{ __('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.') }}
        </p>

        <button 
            wire:click="$set('confirmingUserDeletion', true)" 
            wire:loading.attr="disabled"
            class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold shadow-sm hover:bg-red-700 transition">
            {{ __('Sí, borrar mi cuenta') }}
        </button>

        @if($confirmingUserDeletion)
            <div class="mt-6 p-4 bg-white rounded-lg border border-red-200 shadow-lg animation-fade-in">
                <h4 class="text-md font-bold text-secondary-900 mb-2">{{ __('Confirma tu contraseña para continuar') }}</h4>
                
                <div class="mb-4">
                    <input 
                        wire:model="password" 
                        type="password" 
                        class="block w-full rounded-lg border-secondary-300 shadow-sm focus:border-red-500 focus:ring-red-500" 
                        placeholder="Escribe tu contraseña"
                        wire:keydown.enter="deleteUser" />
                    
                    @error('password') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <button 
                        wire:click="$set('confirmingUserDeletion', false)" 
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                        {{ __('Cancelar') }}
                    </button>

                    <button 
                        wire:click="deleteUser" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 shadow-md">
                        {{ __('Eliminar Definitivamente') }}
                    </button>
                </div>
            </div>
        @endif
    </div>
</section>