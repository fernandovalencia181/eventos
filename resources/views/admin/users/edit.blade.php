<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Editar Usuario: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cabecera con Botón Volver -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="relative w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300 flex-shrink-0">
                        <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                        <div class="relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">Editar Perfil</h1>
                        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Modifica los datos personales y rol del usuario</p>
                    </div>
                </div>
                
                <a href="{{ route('users.index') }}" 
                   class="w-full sm:w-auto justify-center bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 border border-secondary-300 dark:border-primary-600 px-5 py-2.5 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 transition shadow-sm inline-flex items-center gap-2 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Volver al Directorio
                </a>
            </div>

            <div class="bg-white dark:bg-primary-900 overflow-hidden shadow-xl rounded-2xl border border-secondary-200 dark:border-primary-800 p-6 sm:p-8">

                <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Nombre</label>
                        <input id="name" class="block mt-1 w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-pink-500 dark:focus:border-pink-500 focus:ring-pink-500 dark:focus:ring-pink-500 transition-colors py-3 px-4" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Email</label>
                        <input id="email" class="block mt-1 w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-pink-500 dark:focus:border-pink-500 focus:ring-pink-500 dark:focus:ring-pink-500 transition-colors py-3 px-4" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Teléfono</label>
                        <input id="phone" class="block mt-1 w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-pink-500 dark:focus:border-pink-500 focus:ring-pink-500 dark:focus:ring-pink-500 transition-colors py-3 px-4" type="text" name="phone" value="{{ old('phone', $user->phone) }}" />
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="course" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Ciclo (Nivel o Grado)</label>
                        @php
                            $lastRegistration = $user->registrations->sortByDesc('created_at')->first();
                            $currentCourse = $lastRegistration ? $lastRegistration->course : '';
                        @endphp
                        <input id="course" class="block mt-1 w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-pink-500 dark:focus:border-pink-500 focus:ring-pink-500 dark:focus:ring-pink-500 transition-colors py-3 px-4" type="text" name="course" value="{{ old('course', $currentCourse) }}" placeholder="Ej: 5to Semestre, Graduado..." />
                        @error('course') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="rol" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Rol de Acceso</label>
                        <div class="relative">
                            <select name="rol" id="rol" class="block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-pink-500 dark:focus:border-pink-600 focus:ring-pink-500 dark:focus:ring-pink-600 sm:text-sm py-3 transition-colors appearance-none p-4 cursor-pointer">
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
