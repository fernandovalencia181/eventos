@extends('staff.layout')

@section('title', 'Validación de Entradas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-900 dark:text-white">Validación Manual de Entradas</h1>
        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Buscar y validar entradas por código o nombre</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Formulario de Búsqueda -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
                <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-4">Buscar Entrada</h3>
                
                <form id="buscarForm" class="space-y-4">
                    @csrf
                    <!-- Evento -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Evento</label>
                        <select id="evento_id" name="evento_id" class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                            <option value="">Selecciona un evento...</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Código o Nombre</label>
                        <input type="text" 
                               id="busqueda" 
                               name="busqueda" 
                               placeholder="Ingresa el código QR o nombre del asistente..."
                               class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                    </div>

                    <button type="submit" class="w-full bg-primary-600 text-white py-3 rounded-lg hover:bg-primary-700 font-medium transition">
                        🔍 Buscar Entrada
                    </button>
                </form>

                <!-- Resultado de Búsqueda -->
                <div id="resultado" class="mt-6 hidden">
                    <!-- Aquí se mostrarán los resultados -->
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="space-y-6">
            <!-- Validadas Hoy -->
            <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Validadas Hoy</h3>
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600 dark:text-green-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['validadas_hoy'] ?? 0 }}</p>
            </div>

            <!-- Pendientes -->
            <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Pendientes</h3>
                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-yellow-600 dark:text-yellow-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['pendientes'] ?? 0 }}</p>
            </div>

            <!-- Rechazadas -->
            <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-secondary-600 dark:text-secondary-400">Rechazadas</h3>
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-600 dark:text-red-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-secondary-900 dark:text-white">{{ $stats['rechazadas'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Últimas Validaciones -->
    <div class="mt-8">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800">
            <div class="px-6 py-4 border-b border-secondary-200 dark:border-primary-800">
                <h3 class="text-lg font-bold text-secondary-900 dark:text-white">Últimas Validaciones</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-secondary-200 dark:divide-primary-800">
                    <thead class="bg-secondary-50 dark:bg-primary-950">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Asistente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Evento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="ultimasValidaciones" class="bg-white dark:bg-primary-900 divide-y divide-secondary-200 dark:divide-primary-800">
                        @forelse($ultimas ?? [] as $validacion)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-secondary-900 dark:text-white">
                                {{ Str::limit($validacion->codigo, 8) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900 dark:text-white">
                                {{ $validacion->nombre_asistente }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-600 dark:text-secondary-400">
                                {{ $validacion->evento->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-600 dark:text-secondary-400">
                                {{ $validacion->created_at->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                    Validada
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-secondary-500 dark:text-secondary-400">
                                No hay validaciones recientes
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.getElementById('buscarForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const eventoId = document.getElementById('evento_id').value;
    const busqueda = document.getElementById('busqueda').value;
    
    if (!eventoId || !busqueda) {
        alert('Por favor completa todos los campos');
        return;
    }
    
    const resultado = document.getElementById('resultado');
    resultado.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mx-auto"></div></div>';
    resultado.classList.remove('hidden');
    
    try {
        const response = await fetch('{{ route("staff.buscar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ evento_id: eventoId, busqueda: busqueda })
        });
        
        const data = await response.json();
        
        if (data.success && data.ticket) {
            const ticket = data.ticket;
            resultado.innerHTML = `
                <div class="border-t border-secondary-200 dark:border-primary-800 pt-4">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <h4 class="font-bold text-green-800 dark:text-green-400 mb-3">✅ Entrada Encontrada</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium text-secondary-700 dark:text-secondary-300">Código:</span> <span class="font-mono text-secondary-900 dark:text-white">${ticket.token_seguridad_qr}</span></p>
                            <p><span class="font-medium text-secondary-700 dark:text-secondary-300">Asistente:</span> <span class="text-secondary-900 dark:text-white">${ticket.nombre_asistente || 'N/A'}</span></p>
                            <p><span class="font-medium text-secondary-700 dark:text-secondary-300">Estado:</span> <span class="text-secondary-900 dark:text-white">${ticket.estado}</span></p>
                        </div>
                        ${ticket.estado === 'generada' ? `
                        <button onclick="validarEntrada('${ticket.id}')" class="mt-4 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 font-medium transition">
                            ✓ Validar Entrada
                        </button>
                        ` : `
                        <div class="mt-4 bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-700 text-yellow-800 dark:text-yellow-400 px-4 py-2 rounded-lg text-center">
                            Ya validada anteriormente
                        </div>
                        `}
                    </div>
                </div>
            `;
        } else {
            resultado.innerHTML = `
                <div class="border-t border-secondary-200 dark:border-primary-800 pt-4">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 px-4 py-3 rounded-lg">
                        ❌ ${data.mensaje || 'Entrada no encontrada'}
                    </div>
                </div>
            `;
        }
    } catch (error) {
        resultado.innerHTML = `
            <div class="border-t border-secondary-200 dark:border-primary-800 pt-4">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 px-4 py-3 rounded-lg">
                    Error al buscar la entrada
                </div>
            </div>
        `;
    }
});

async function validarEntrada(ticketId) {
    if (!confirm('¿Confirmar validación de esta entrada?')) return;
    
    try {
        const response = await fetch('{{ route("staff.validar-manual") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ ticket_id: ticketId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✅ Entrada validada correctamente');
            location.reload();
        } else {
            alert('❌ ' + (data.mensaje || 'Error al validar'));
        }
    } catch (error) {
        alert('Error al validar la entrada');
    }
}
</script>
@endsection
