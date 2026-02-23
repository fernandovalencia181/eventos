<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Crear Cuenta de Staff
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="relative w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300 flex-shrink-0">
                        <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                        <div class="relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Nuevo Staff</h1>
                        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Registra un nuevo miembro del staff</p>
                    </div>
                </div>

                <a href="{{ route('admin.staff.index') }}" 
                   class="w-full sm:w-auto justify-center bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 border border-secondary-300 dark:border-primary-600 px-5 py-2.5 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 transition shadow-sm inline-flex items-center gap-2 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Volver a Staff
                </a>
            </div>

            <div class="bg-white dark:bg-primary-900 overflow-hidden shadow-xl rounded-2xl border border-secondary-200 dark:border-primary-800 p-6 sm:p-8">

                <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-6">
                    @csrf
                                        
                    <div>
                        <label for="name" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Nombre Completo</label>
                        <input id="name" class="block mt-1 w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors py-3 px-4" type="text" name="name" value="{{ old('name') }}" required autofocus />
                    </div>

                    <div>
                        <label for="email" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Correo Electrónico (Login)</label>
                        <input id="email" class="block mt-1 w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors py-3 px-4" type="email" name="email" value="{{ old('email') }}" required />
                    </div>
                    
                    <div>
                        <label for="phone" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Teléfono (Opcional)</label>
                        <input id="phone" class="block mt-1 w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors py-3 px-4" type="text" name="phone" value="{{ old('phone') }}" />
                    </div>

                    <div>
                        <label for="password" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Contraseña</label>
                        <input id="password" class="block mt-1 w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors py-3 px-4" type="password" name="password" required autocomplete="new-password" />
                    </div>

                    <div>
                        <label for="evento_id" class="block font-medium text-sm text-secondary-700 dark:text-secondary-300 mb-1">Asignar a Evento Principal</label>
                        <div class="relative">
                            <select name="evento_id" id="evento_id" class="block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 transition-colors appearance-none p-4 cursor-pointer" required>
                                <option value="">-- Seleccionar Evento --</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}" {{ old('evento_id') == $evento->id ? 'selected' : '' }}>
                                        {{ $evento->fecha }} - {{ Str::limit($evento->nombre, 40) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-secondary-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-2">El staff se logueará y verá directamente este evento para escanear.</p>
                    </div>

                    <div class="bg-secondary-50 dark:bg-primary-800/50 p-4 rounded-xl border border-secondary-100 dark:border-primary-800">
                        <label class="flex items-start space-x-3 cursor-pointer">
                            <input type="checkbox" name="access_guests" value="1" {{ old('access_guests') ? 'checked' : '' }} class="mt-1 rounded border-secondary-300 dark:border-primary-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-primary-700">
                            <div>
                                <span class="block text-sm font-bold text-secondary-800 dark:text-secondary-200">Habilitar vista de invitados (Check-in manual)</span>
                                <span class="text-xs text-secondary-500 dark:text-secondary-400 block mt-1">Permite buscar invitados manualmente en todos los eventos, útil para recepcionistas.</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 pt-4 border-t border-secondary-100 dark:border-primary-800">
                        <a href="{{ route('admin.staff.index') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 border border-secondary-300 dark:border-primary-600 font-bold transition-colors">Cancelar</a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 font-bold shadow-lg transform hover:scale-105 transition duration-200">Crear Cuenta</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
