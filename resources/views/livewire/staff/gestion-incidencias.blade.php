<div>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-secondary-900">
            🚨 Gestión de Incidencias
        </h1>
        <button 
            wire:click="$set('mostrarFormulario', {{ !$mostrarFormulario }})"
            class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-medium transition-colors"
        >
            @if($mostrarFormulario) ❌ Cancelar @else ➕ Reportar Incidencia @endif
        </button>
    </div>

    <!-- Mensajes Flash -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario Nueva Incidencia -->
    @if($mostrarFormulario)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-secondary-100">
            <h2 class="text-xl font-semibold text-secondary-900 mb-4">
                Reportar Nueva Incidencia
            </h2>
            
            <form wire:submit.prevent="guardarIncidencia" class="space-y-4">
                <!-- Tipo de Incidencia -->
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">
                        Tipo de Incidencia
                    </label>
                    <select wire:model="tipo" class="w-full px-4 py-2 border border-secondary-300 rounded-xl">
                        <option value="perdida_qr">Pérdida de QR</option>
                        <option value="error_datos">Error en Datos</option>
                        <option value="doble_entrada">Doble Entrada</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <!-- Búsqueda de Usuario -->
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">
                        Usuario Afectado (opcional)
                    </label>
                    <input 
                        type="text" 
                        wire:model.live="busquedaUsuario"
                        placeholder="Buscar por nombre o email..."
                        class="w-full px-4 py-2 border border-secondary-300 rounded-xl"
                    >
                    
                    @if(count($usuariosEncontrados) > 0)
                        <div class="mt-2 border border-secondary-200 rounded-xl divide-y divide-secondary-200 max-h-48 overflow-y-auto">
                            @foreach($usuariosEncontrados as $usuario)
                                <div 
                                    wire:click="seleccionarUsuario({{ $usuario->id }})"
                                    class="p-3 hover:bg-secondary-50 cursor-pointer"
                                >
                                    <p class="font-medium text-secondary-900">{{ $usuario->name }}</p>
                                    <p class="text-sm text-secondary-500">{{ $usuario->email }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">
                        Descripción de la Incidencia *
                    </label>
                    <textarea 
                        wire:model="descripcion"
                        rows="4"
                        placeholder="Describe detalladamente qué sucedió..."
                        class="w-full px-4 py-2 border border-secondary-300 rounded-xl"
                    ></textarea>
                    @error('descripcion') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 font-medium"
                    >
                        📝 Guardar Incidencia
                    </button>
                    <button 
                        type="button"
                        wire:click="$set('mostrarFormulario', false)"
                        class="px-6 py-3 bg-secondary-600 text-white rounded-xl hover:bg-secondary-700 font-medium"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
        <div class="flex gap-4">
            <button 
                wire:click="$set('filtroEstado', 'todas')"
                class="px-4 py-2 rounded-xl font-medium @if($filtroEstado === 'todas') bg-blue-600 text-white @else bg-secondary-200 text-secondary-700 @endif"
            >
                Todas
            </button>
            <button 
                wire:click="$set('filtroEstado', 'pendiente')"
                class="px-4 py-2 rounded-xl font-medium @if($filtroEstado === 'pendiente') bg-yellow-600 text-white @else bg-secondary-200 text-secondary-700 @endif"
            >
                Pendientes
            </button>
            <button 
                wire:click="$set('filtroEstado', 'resuelto')"
                class="px-4 py-2 rounded-xl font-medium @if($filtroEstado === 'resuelto') bg-green-600 text-white @else bg-secondary-200 text-secondary-700 @endif"
            >
                Resueltas
            </button>
            <button 
                wire:click="$set('filtroEstado', 'escalado')"
                class="px-4 py-2 rounded-xl font-medium @if($filtroEstado === 'escalado') bg-red-600 text-white @else bg-secondary-200 text-secondary-700 @endif"
            >
                Escaladas
            </button>
        </div>
    </div>

    <!-- Lista de Incidencias -->
    <div class="space-y-4">
        @forelse($incidencias as $incidencia)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold @if($incidencia->estado === 'pendiente') bg-yellow-100 text-yellow-800 @elseif($incidencia->estado === 'resuelto') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ strtoupper($incidencia->estado) }}
                            </span>
                            <span class="px-3 py-1 bg-secondary-100 rounded-full text-xs font-medium text-secondary-700">
                                {{ ucfirst(str_replace('_', ' ', $incidencia->tipo)) }}
                            </span>
                        </div>
                        
                        @if($incidencia->user)
                            <p class="text-sm text-secondary-600 mb-2">
                                👤 Usuario: <span class="font-medium">{{ $incidencia->user->name }}</span>
                            </p>
                        @endif
                        
                        <p class="text-secondary-900 mb-3">
                            {{ $incidencia->descripcion }}
                        </p>
                        
                        <div class="text-xs text-secondary-500 space-y-1">
                            <p>📅 Reportado: {{ $incidencia->created_at->format('d/m/Y H:i') }} por {{ $incidencia->reportador->name }}</p>
                            @if($incidencia->estado === 'resuelto')
                                <p>✅ Resuelto: {{ $incidencia->fecha_resolucion->format('d/m/Y H:i') }} por {{ $incidencia->resolutor->name }}</p>
                                <p class="text-green-600 mt-2">
                                    💡 Solución: {{ $incidencia->solucion }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    @if($incidencia->estado === 'pendiente')
                        <div class="flex gap-2 ml-4">
                            <button 
                                wire:click="editarIncidencia({{ $incidencia->id }})"
                                class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 text-sm font-medium"
                            >
                                ✅ Resolver
                            </button>
                            <button 
                                wire:click="escalarIncidencia({{ $incidencia->id }})"
                                class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-medium"
                            >
                                ⬆️ Escalar
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <p class="text-secondary-500 text-lg">
                    No hay incidencias registradas
                </p>
            </div>
        @endforelse
    </div>

    <!-- Modal Resolver Incidencia -->
    @if($incidenciaEditando)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 p-6">
                <h3 class="text-2xl font-bold text-secondary-900 mb-4">
                    Resolver Incidencia
                </h3>
                
                <div class="mb-4">
                    <p class="text-secondary-600 mb-2">
                        <strong>Descripción:</strong> {{ $incidenciaEditando->descripcion }}
                    </p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">
                        Solución Aplicada *
                    </label>
                    <textarea 
                        wire:model="solucion"
                        rows="4"
                        placeholder="Describe cómo se resolvió la incidencia..."
                        class="w-full px-4 py-2 border border-secondary-300 rounded-xl"
                    ></textarea>
                    @error('solucion') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div class="flex gap-4">
                    <button 
                        wire:click="resolverIncidencia"
                        class="flex-1 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold"
                    >
                        ✅ Marcar como Resuelta
                    </button>
                    <button 
                        wire:click="$set('incidenciaEditando', null)"
                        class="px-6 py-3 bg-secondary-600 text-white rounded-xl hover:bg-secondary-700 font-bold"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
