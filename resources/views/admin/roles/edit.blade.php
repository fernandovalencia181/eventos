<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Editar Rol: {{ $role->label }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-primary-900 overflow-hidden shadow-xl sm:rounded-xl border border-secondary-200 dark:border-primary-800 p-8">
                
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-secondary-900 dark:text-white">Configuración de Rol</h3>
                    <p class="text-secondary-500 dark:text-secondary-400">Edita los detalles y permisos del rol</p>
                </div>

                <form action="{{ route('roles.update', $role) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                                        
                    <div>
                        <x-label value="Identificador" />
                        <x-input class="block mt-1 w-full bg-secondary-100 dark:bg-primary-950 text-secondary-500 dark:text-secondary-400 cursor-not-allowed" type="text" value="{{ $role->name }}" disabled />
                    </div>

                    <div>
                        <x-label for="label" value="Nombre Visible" />
                        <x-input id="label" class="block mt-1 w-full" type="text" name="label" value="{{ $role->label }}" required />
                    </div>

                    <div class="bg-secondary-50 dark:bg-primary-800/50 p-4 rounded-xl border border-secondary-100 dark:border-primary-800">
                        <h3 class="text-sm font-bold text-secondary-800 dark:text-secondary-200 mb-3">Permisos Especiales</h3>
                        <label class="flex items-start space-x-3 cursor-pointer">
                            <input type="checkbox" name="access_guests" value="1" 
                                {{ (isset($role->permissions['access_guests']) && $role->permissions['access_guests']) ? 'checked' : '' }}
                                class="mt-1 rounded border-secondary-300 dark:border-primary-600 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 dark:bg-primary-700">
                            <div>
                                <span class="block text-sm font-bold text-secondary-800 dark:text-secondary-200">Permitir gestión de Invitados (Staff)</span>
                                <span class="text-xs text-secondary-500 dark:text-secondary-400 block mt-1">Habilita la sección de invitados y escaneo relacionado.</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-secondary-100 dark:border-primary-800">
                        <a href="{{ route('roles.index') }}" class="mr-4 px-6 py-3 bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 border border-secondary-300 dark:border-primary-600 font-bold transition-colors">Cancelar</a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 font-bold shadow-lg transform hover:scale-105 transition duration-200">Actualizar Rol</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
