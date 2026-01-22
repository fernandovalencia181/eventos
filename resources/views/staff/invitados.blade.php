@extends('staff.layout')

@section('title', 'Lista de Invitados')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 dark:text-white">Lista de Invitados</h1>
            <p class="text-secondary-600 dark:text-secondary-400 mt-2">Gestión de acceso VIP y lista de invitados</p>
        </div>
        <button onclick="document.getElementById('modal_invitado').classList.remove('hidden')" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition shadow-md">
            + Agregar Invitado
        </button>
    </div>

    <!-- Búsqueda y filtros -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" placeholder="Buscar por nombre..." class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-3 text-secondary-900 dark:text-white">
            <select class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-3 text-secondary-900 dark:text-white">
                <option value="">Todos los eventos</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                @endforeach
            </select>
            <select class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-3 text-secondary-900 dark:text-white">
                <option value="">Estado: Todos</option>
                <option value="pendiente">Pendiente</option>
                <option value="confirmado">Confirmado</option>
                <option value="validado">Validado</option>
            </select>
        </div>
    </div>

    <!-- Tabla de invitados -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-secondary-200 dark:divide-primary-800">
                <thead class="bg-secondary-50 dark:bg-primary-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Evento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-primary-900 divide-y divide-secondary-200 dark:divide-primary-800">
                    @forelse($invitados ?? [] as $invitado)
                    <tr class="hover:bg-secondary-50 dark:hover:bg-primary-800 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($invitado->nombre, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-secondary-900 dark:text-white">{{ $invitado->nombre }}</div>
                                    <div class="text-sm text-secondary-500 dark:text-secondary-400">{{ $invitado->tipo ?? 'VIP' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900 dark:text-white">
                            {{ $invitado->evento->nombre ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-600 dark:text-secondary-400">
                            {{ $invitado->email ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $invitado->estado === 'validado' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                   ($invitado->estado === 'confirmado' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 
                                   'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400') }}">
                                {{ ucfirst($invitado->estado ?? 'pendiente') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">Ver</button>
                            <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Eliminar</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-secondary-600 dark:text-secondary-400">
                            <div class="text-6xl mb-4">👤</div>
                            <p>No hay invitados registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Agregar Invitado -->
<div id="modal_invitado" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-primary-900 rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-secondary-900 dark:text-white mb-4">Nuevo Invitado</h3>
        <form>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Nombre completo</label>
                    <input type="text" class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Email</label>
                    <input type="email" class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Evento</label>
                    <select class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                        <option value="">Seleccionar evento</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('modal_invitado').classList.add('hidden')" class="flex-1 bg-secondary-300 dark:bg-primary-800 text-secondary-900 dark:text-white py-2 rounded-lg hover:bg-secondary-400 dark:hover:bg-primary-700 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-primary-600 text-white py-2 rounded-lg hover:bg-primary-700 transition">
                        Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
