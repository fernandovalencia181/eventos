<div>
    <label class="block text-sm font-medium text-gray-700">Nombre del Evento</label>
    <input type="text" name="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
    @error('nombre') 
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Descripción</label>
    <textarea name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('descripcion') }}</textarea>
    @error('descripcion') 
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
        <input type="datetime-local" name="fecha" value="{{ old('fecha') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        @error('fecha') 
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Lugar</label>
        <input type="text" name="lugar" value="{{ old('lugar') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        @error('lugar') 
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
        @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Aforo Máximo</label>
    <input type="number" name="aforo_maximo" value="{{ old('aforo_maximo') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
    @error('aforo_maximo') 
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
    @enderror
</div>