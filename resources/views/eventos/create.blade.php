<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 

                    <div class="grid grid-cols-1 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Evento</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('descripcion') }}</textarea>
                            @error('descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Imagen del Evento (Opcional)</label>
                            <input type="file" name="imagen" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('imagen') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
                                <input type="datetime-local" name="fecha" min="{{ date('Y-m-d\TH:i') }}" value="{{ old('fecha') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('fecha') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lugar</label>
                                <input type="text" name="lugar" value="{{ old('lugar') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('lugar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Aforo Máximo</label>
                            <input type="number" name="aforo_maximo" value="{{ old('aforo_maximo') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('aforo_maximo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-4 mt-4">
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 font-semibold hover:bg-gray-300">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 rounded-md text-white font-semibold hover:bg-indigo-700">Guardar Evento</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>