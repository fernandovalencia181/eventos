<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Crear Nuevo Usuario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-primary-900 overflow-hidden shadow-xl sm:rounded-xl border border-secondary-200 dark:border-primary-800 p-6 sm:p-8">
                
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-secondary-900 dark:text-white">Nuevo Usuario</h3>
                    <p class="text-secondary-500 dark:text-secondary-400">Registra un nuevo usuario en la plataforma</p>
                </div>

                <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                    @csrf
                                        
                    <div>
                        <label for="name" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Nombre Completo</label>
                        <input id="name" class="block mt-1 w-full border border-secondary-300 dark:border-primary-600 rounded-xl shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm py-3 px-4 dark:bg-primary-800 dark:text-white" type="text" name="name" value="{{ old('name') }}" required autofocus />
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Correo Electrónico</label>
                        <input id="email" class="block mt-1 w-full border border-secondary-300 dark:border-primary-600 rounded-xl shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm py-3 px-4 dark:bg-primary-800 dark:text-white" type="email" name="email" value="{{ old('email') }}" required />
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Teléfono (Opcional)</label>
                        <input id="phone" class="block mt-1 w-full border border-secondary-300 dark:border-primary-600 rounded-xl shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm py-3 px-4 dark:bg-primary-800 dark:text-white" type="text" name="phone" value="{{ old('phone') }}" />
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Contraseña</label>
                            <input id="password" class="block mt-1 w-full border border-secondary-300 dark:border-primary-600 rounded-xl shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm py-3 px-4 dark:bg-primary-800 dark:text-white" type="password" name="password" required autocomplete="new-password" />
                            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Confirmar Contraseña</label>
                            <input id="password_confirmation" class="block mt-1 w-full border border-secondary-300 dark:border-primary-600 rounded-xl shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm py-3 px-4 dark:bg-primary-800 dark:text-white" type="password" name="password_confirmation" required autocomplete="new-password" />
                        </div>
                    </div>

                    <div>
                        <label for="rol" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Rol de Acceso</label>
                        <div class="relative">
                            <select name="rol" id="rol" class="block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm py-3" required>
                                <option value="user" {{ old('rol') == 'user' ? 'selected' : '' }}>Usuario (Clientes)</option>
                                <option value="staff" {{ old('rol') == 'staff' ? 'selected' : '' }}>Staff (Control de Acceso)</option>
                                <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador (Acceso Total)</option>
                            </select>
                        </div>
                        @error('rol') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>


                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 pt-4 border-t border-secondary-100 dark:border-primary-800">
                        <a href="{{ route('admin.staff.index') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 border border-secondary-300 dark:border-primary-600 font-bold transition-colors">Cancelar</a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl hover:from-indigo-700 hover:to-blue-700 font-bold shadow-lg transform hover:scale-105 transition duration-200">Crear Cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
