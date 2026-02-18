<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            {{ __('Crear Nuevo Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cabecera Estilo Staff -->
            <div class="mb-8 flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                <div class="relative w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300 flex-shrink-0">
                    <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                    <div class="relative z-10">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Nuevo Evento</h1>
                    <p class="text-secondary-600 dark:text-secondary-400 mt-2">Configura los detalles del próximo evento</p>
                </div>
            </div>

            <div class="bg-white dark:bg-primary-900 overflow-hidden shadow-xl sm:rounded-xl border border-secondary-200 dark:border-primary-800 p-6 sm:p-8">
                
                <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 

                    <div class="grid grid-cols-1 gap-6">
                        
                        <div>
                            <label class="block text-sm font-bold text-secondary-700 dark:text-secondary-300 mb-2">Nombre del Evento</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="Ej: Graduación 2026">
                            @error('nombre') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-secondary-700 dark:text-secondary-300 mb-2">Descripción</label>
                            <textarea name="descripcion" rows="3" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="Detalles importantes del evento...">{{ old('descripcion') }}</textarea>
                            @error('descripcion') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-secondary-700 dark:text-secondary-300 mb-2">Imagen del Evento (Opcional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-secondary-300 dark:border-primary-600 border-dashed rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-800/50 transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-secondary-600 dark:text-secondary-400">
                                        <label for="imagen" class="relative cursor-pointer bg-white dark:bg-primary-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Sube un archivo</span>
                                            <input id="imagen" name="imagen" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">o arrastra y suelta</p>
                                    </div>
                                    <p class="text-xs text-secondary-500 dark:text-secondary-500">PNG, JPG, GIF hasta 10MB</p>
                                </div>
                            </div>
                            @error('imagen') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-secondary-700 dark:text-secondary-300 mb-2">Fecha y Hora</label>
                                <input type="datetime-local" name="fecha" min="{{ date('Y-m-d\TH:i') }}" value="{{ old('fecha') }}" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('fecha') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-secondary-700 dark:text-secondary-300 mb-2">Lugar</label>
                                <input type="text" name="lugar" value="{{ old('lugar') }}" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="Ej: Auditorio Principal">
                                @error('lugar') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-secondary-700 dark:text-secondary-300 mb-2">Aforo Máximo</label>
                            <input type="number" name="aforo_maximo" value="{{ old('aforo_maximo') }}" class="mt-1 block w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="Ej: 500">
                            @error('aforo_maximo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 mt-8 pt-6 border-t border-secondary-200 dark:border-primary-800">
                            <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-600 rounded-xl text-secondary-700 dark:text-secondary-300 font-bold hover:bg-secondary-50 dark:hover:bg-primary-700 transition text-center">Cancelar</a>
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold hover:from-blue-700 hover:to-indigo-700 shadow-lg transform hover:scale-105 transition">Crear Evento</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>