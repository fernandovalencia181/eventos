@extends('staff.layout')

@section('title', 'Gestión de Invitados Especiales')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">⭐ Gestión de Invitados Especiales</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Registro de ponentes, personal externo y VIPs</p>
            </div>
            <button onclick="document.getElementById('modal_invitado').classList.remove('hidden')" 
                    class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition shadow-md inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                Agregar Invitado
            </button>
        </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Total Registrados</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-purple-600 dark:text-purple-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $invitados->count() }}</p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Pendientes</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-yellow-600 dark:text-yellow-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $invitados->where('estado', 'pendiente')->count() }}</p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Confirmados</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600 dark:text-blue-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $invitados->where('estado', 'confirmado')->count() }}</p>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Validados</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600 dark:text-green-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
            </div>
            <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $invitados->where('estado', 'validado')->count() }}</p>
        </div>
    </div>

    <!-- Búsqueda y filtros -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" 
                   id="buscar_invitado" 
                   placeholder="Buscar por nombre, email o empresa..." 
                   class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-3 text-secondary-900 dark:text-white">
            <select id="filtro_evento" class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-3 text-secondary-900 dark:text-white">
                <option value="">Todos los eventos</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                @endforeach
            </select>
            <select id="filtro_estado" class="bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-3 text-secondary-900 dark:text-white">
                <option value="">Estado: Todos</option>
                <option value="pendiente">Pendiente</option>
                <option value="confirmado">Confirmado</option>
                <option value="validado">Validado</option>
            </select>
        </div>
    </div>

    <!-- Tabla de invitados -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-secondary-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-secondary-200" id="tabla-invitados">
                <thead class="bg-secondary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Invitado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Evento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Cargo/Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-secondary-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-secondary-200">
                    @forelse($invitados as $invitado)
                    <tr class="hover:bg-secondary-50 transition" 
                        data-nombre="{{ strtolower($invitado->nombre) }}" 
                        data-email="{{ strtolower($invitado->email ?? '') }}" 
                        data-empresa="{{ strtolower($invitado->empresa ?? '') }}"
                        data-evento="{{ $invitado->evento_id }}"
                        data-estado="{{ $invitado->estado }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                    {{ substr($invitado->nombre, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-secondary-900 dark:text-white">{{ $invitado->nombre }}</p>
                                    <p class="text-xs text-secondary-600 dark:text-secondary-400">ID: {{ $invitado->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary-900 dark:text-white">
                            <p class="font-medium">{{ $invitado->evento->nombre ?? 'N/A' }}</p>
                            <p class="text-xs text-secondary-600 dark:text-secondary-400">
                                {{ $invitado->evento->fecha->format('d/m/Y') ?? '' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <p class="text-secondary-900 dark:text-white">{{ $invitado->email ?? 'N/A' }}</p>
                            <p class="text-xs text-secondary-600 dark:text-secondary-400">{{ $invitado->telefono ?? 'Sin teléfono' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <p class="text-secondary-900 dark:text-white font-medium">{{ $invitado->cargo ?? 'N/A' }}</p>
                            <p class="text-xs text-secondary-600 dark:text-secondary-400">{{ $invitado->empresa ?? 'Sin empresa' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $invitado->estado === 'validado' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                   ($invitado->estado === 'confirmado' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 
                                   'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400') }}">
                                {{ ucfirst($invitado->estado ?? 'pendiente') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="verDetalles({{ $invitado->id }})" 
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                                @if($invitado->notas)
                                <span class="text-gray-400" title="{{ $invitado->notas }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-6xl mb-4">👥</div>
                            <p class="text-lg font-medium text-secondary-900 dark:text-white mb-2">No hay invitados registrados</p>
                            <p class="text-secondary-600 dark:text-secondary-400">Agrega ponentes, personal externo o VIPs</p>
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
    <div class="bg-white dark:bg-primary-900 rounded-lg max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-secondary-900 dark:text-white mb-6">⭐ Registrar Invitado Especial</h3>
        <form method="POST" action="{{ route('staff.registrar-invitado') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Nombre completo *</label>
                    <input type="text" 
                           name="nombre" 
                           required 
                           placeholder="Ej: Dr. Juan Pérez García"
                           class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Email</label>
                    <input type="email" 
                           name="email" 
                           placeholder="invitado@email.com"
                           class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Teléfono</label>
                    <input type="tel" 
                           name="telefono" 
                           placeholder="+52 123 456 7890"
                           class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Cargo</label>
                    <input type="text" 
                           name="cargo" 
                           placeholder="Ej: Ponente, Conferencista"
                           class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Empresa/Institución</label>
                    <input type="text" 
                           name="empresa" 
                           placeholder="Nombre de la organización"
                           class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Evento *</label>
                    <select name="evento_id" required class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                        <option value="">Seleccionar evento...</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Notas adicionales</label>
                    <textarea name="notas" 
                              rows="3" 
                              placeholder="Información relevante sobre el invitado..."
                              class="w-full bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white"></textarea>
                </div>
            </div>
            <div class="flex gap-3 pt-6">
                <button type="button" 
                        onclick="document.getElementById('modal_invitado').classList.add('hidden')" 
                        class="flex-1 bg-secondary-300 dark:bg-primary-800 text-secondary-900 dark:text-white py-3 rounded-lg hover:bg-secondary-400 dark:hover:bg-primary-700 transition font-medium">
                    Cancelar
                </button>
                <button type="submit" 
                        class="flex-1 bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition font-medium">
                    Registrar Invitado
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Búsqueda en tiempo real
    const buscar = document.getElementById('buscar_invitado');
    const filtroEvento = document.getElementById('filtro_evento');
    const filtroEstado = document.getElementById('filtro_estado');
    
    function aplicarFiltros() {
        const search = buscar.value.toLowerCase();
        const evento = filtroEvento.value;
        const estado = filtroEstado.value;
        
        const rows = document.querySelectorAll('#tabla-invitados tbody tr');
        
        rows.forEach(row => {
            const nombre = row.getAttribute('data-nombre') || '';
            const email = row.getAttribute('data-email') || '';
            const empresa = row.getAttribute('data-empresa') || '';
            const rowEvento = row.getAttribute('data-evento') || '';
            const rowEstado = row.getAttribute('data-estado') || '';
            
            let mostrar = true;
            
            // Filtro de búsqueda
            if (search && !nombre.includes(search) && !email.includes(search) && !empresa.includes(search)) {
                mostrar = false;
            }
            
            // Filtro de evento
            if (evento && rowEvento !== evento) {
                mostrar = false;
            }
            
            // Filtro de estado
            if (estado && rowEstado !== estado) {
                mostrar = false;
            }
            
            row.style.display = mostrar ? '' : 'none';
        });
    }
    
    buscar.addEventListener('input', aplicarFiltros);
    filtroEvento.addEventListener('change', aplicarFiltros);
    filtroEstado.addEventListener('change', aplicarFiltros);
    
    function verDetalles(id) {
        // Implementar vista de detalles
        console.log('Ver detalles del invitado:', id);
    }
</script>
@endsection
