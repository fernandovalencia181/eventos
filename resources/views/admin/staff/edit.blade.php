<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Editar Staff: {{ $staff->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-primary-900 overflow-hidden shadow-xl sm:rounded-xl border border-secondary-200 dark:border-primary-800 p-6 sm:p-8">
                
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-secondary-900 dark:text-white">Actualizar Información</h3>
                    <p class="text-secondary-500 dark:text-secondary-400">Modifica los datos y permisos del miembro del staff</p>
                </div>

                <form action="{{ route('admin.staff.update', $staff) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                                        
                    <div>
                        <x-label for="name" value="Nombre Completo" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $staff->name }}" required />
                    </div>

                    <div>
                        <x-label for="email" value="Correo Electrónico" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ $staff->email }}" required />
                    </div>
                    
                    <div>
                        <x-label for="phone" value="Teléfono" />
                        <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" value="{{ $staff->phone }}" />
                    </div>

                    <div>
                        <x-label for="password" value="Nueva Contraseña (Dejar en blanco para no cambiar)" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                    </div>

                    <div>
                        <label for="evento_id" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Evento Asignado</label>
                        <div class="relative">
                            <select name="evento_id" id="evento_id" class="block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm py-3" required>
                                <option value="">-- Seleccionar Evento --</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}" {{ $staff->evento_id == $evento->id ? 'selected' : '' }}>
                                        {{ $evento->fecha }} - {{ Str::limit($evento->nombre, 40) }}
                                    </option>
                                @endforeach
                            </select>
                             <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-secondary-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-secondary-50 dark:bg-primary-800/50 p-4 rounded-xl border border-secondary-100 dark:border-primary-800">
                        <label class="flex items-start space-x-3 cursor-pointer">
                            <input type="checkbox" name="access_guests" {{ $staff->hasPermission('access_guests') ? 'checked' : '' }} class="mt-1 rounded border-secondary-300 dark:border-primary-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-primary-700">
                            <div>
                                <span class="block text-sm font-bold text-secondary-800 dark:text-secondary-200">Habilitar vista de invitados (Check-in manual)</span>
                                <span class="text-xs text-secondary-500 dark:text-secondary-400 block mt-1">Permite buscar invitados manualmente en todos los eventos.</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 pt-4 border-t border-secondary-100 dark:border-primary-800">
                        <a href="{{ route('admin.staff.index') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 border border-secondary-300 dark:border-primary-600 font-bold transition-colors">Cancelar</a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl hover:from-indigo-700 hover:to-blue-700 font-bold shadow-lg transform hover:scale-105 transition duration-200">Actualizar Staff</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
