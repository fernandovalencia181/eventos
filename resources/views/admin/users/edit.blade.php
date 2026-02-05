<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Editar Usuario: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-primary-900 overflow-hidden shadow-xl sm:rounded-xl border border-secondary-200 dark:border-primary-800 p-6 sm:p-8">
                
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-secondary-900 dark:text-white">Editar Perfil</h3>
                    <p class="text-secondary-500 dark:text-secondary-400">Modifica los datos personales y rol del usuario</p>
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-label for="name" value="Nombre" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-label for="email" value="Email" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-label for="phone" value="Teléfono" />
                        <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $user->phone)" />
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="rol" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Rol de Acceso</label>
                        <div class="relative">
                            <select name="rol" id="rol" class="block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-pink-500 dark:focus:border-pink-600 focus:ring-pink-500 dark:focus:ring-pink-600 sm:text-sm py-3 transition-colors appearance-none p-4">
                                <option value="user" {{ old('rol', $user->rol) == 'user' ? 'selected' : '' }}>Usuario (Clientes)</option>
                                <option value="staff" {{ old('rol', $user->rol) == 'staff' ? 'selected' : '' }}>Staff (Control de Acceso)</option>
                                <option value="admin" {{ old('rol', $user->rol) == 'admin' ? 'selected' : '' }}>Administrador (Acceso Total)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-secondary-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('rol') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 pt-4 border-t border-secondary-100 dark:border-primary-800">
                        <a href="{{ route('users.index') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 border border-secondary-300 dark:border-primary-600 font-bold transition-colors">Cancelar</a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-xl hover:from-pink-700 hover:to-rose-700 font-bold shadow-lg transform hover:scale-105 transition duration-200">
                            Actualizar Usuario
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
