<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Equipo de Staff
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Cabecera Estilo Staff -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="relative w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                        <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                        <div class="relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Gestión de Staff</h1>
                        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Control de personal y permisos de acceso</p>
                    </div>
                </div>

                <a href="{{ route('admin.staff.create') }}" class="w-full md:w-auto bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl hover:from-green-700 hover:to-emerald-700 shadow-lg transform hover:scale-105 transition font-bold flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Nuevo Staff
                </a>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl relative shadow-sm">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="hidden md:block bg-white dark:bg-primary-900 overflow-hidden shadow-xl sm:rounded-xl border border-secondary-200 dark:border-primary-800 p-1">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-secondary-200 dark:border-primary-800">
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Nombre / Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Evento Asignado</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Acceso Invitados</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Fecha Alta</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100 dark:divide-primary-800">
                        @forelse ($staffUsers as $staff)
                        <tr class="hover:bg-secondary-50 dark:hover:bg-primary-800/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-secondary-900 dark:text-white">{{ $staff->name }}</div>
                                <div class="text-sm text-secondary-500 dark:text-secondary-400">{{ $staff->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($staff->evento)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full text-xs font-semibold border border-blue-200 dark:border-blue-800">
                                        {{ Str::limit($staff->evento->nombre, 30) }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded-full text-xs font-semibold border border-red-200 dark:border-red-800">
                                        Sin asignar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Modern Toggle -->
                                <label class="relative inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" 
                                           class="sr-only peer" 
                                           onchange="togglePermission({{ $staff->id }}, 'access_guests', this.checked)"
                                           {{ $staff->hasPermission('access_guests') ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 dark:after:border-gray-600 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm font-medium text-secondary-500 dark:text-secondary-400 group-hover:text-secondary-700 dark:group-hover:text-secondary-300 transition-colors">
                                        {{ $staff->hasPermission('access_guests') ? 'Permitido' : 'Denegado' }}
                                    </span>
                                </label>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500 dark:text-secondary-400">
                                {{ $staff->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.staff.edit', $staff) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3 font-bold transition-colors">Editar</a>
                                <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Borrar esta cuenta de staff?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-bold transition-colors">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-secondary-500 dark:text-secondary-400 border-t border-secondary-100 dark:border-primary-800">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-secondary-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <span class="text-lg font-medium">No hay staff registrado</span>
                                    <p class="text-sm mt-1">Comienza añadiendo un nuevo miembro al equipo.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- VISTA DE MÓVIL (Tarjetas) -->
            <div class="md:hidden space-y-4">
                @forelse ($staffUsers as $staff)
                    <div class="bg-white dark:bg-primary-900 rounded-xl shadow-md p-5 border border-secondary-200 dark:border-primary-800">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-secondary-900 dark:text-white text-lg leading-tight">{{ $staff->name }}</h3>
                                <p class="text-sm text-secondary-500 dark:text-secondary-400">{{ $staff->email }}</p>
                            </div>
                            @if($staff->evento)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-lg text-xs font-semibold border border-blue-200 dark:border-blue-800">
                                    {{ Str::limit($staff->evento->nombre, 10) }}
                                </span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded-lg text-xs font-semibold border border-red-200 dark:border-red-800">
                                    Sin evento
                                </span>
                            @endif
                        </div>

                        <!-- Toggle Guest Access (Simplified for Mobile) -->
                        <div class="mb-4 bg-secondary-50 dark:bg-primary-800/50 p-3 rounded-xl flex items-center justify-between">
                            <span class="text-sm font-medium text-secondary-600 dark:text-secondary-300">Acceso Invitados</span>
                             <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" 
                                       class="sr-only peer" 
                                       onchange="togglePermission({{ $staff->id }}, 'access_guests', this.checked)"
                                       {{ $staff->hasPermission('access_guests') ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 dark:after:border-gray-600 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-secondary-100 dark:border-primary-800">
                            <a href="{{ route('admin.staff.edit', $staff) }}" class="flex justify-center items-center py-2 bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-bold border border-blue-100 dark:border-blue-800">
                                Editar
                            </a>
                            <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" onsubmit="return confirm('¿Borrar?');" class="w-full">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-2 bg-red-50 dark:bg-red-900/40 text-red-700 dark:text-red-300 rounded-lg text-sm font-bold border border-red-100 dark:border-red-800">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 bg-white dark:bg-primary-900 rounded-lg border border-dashed border-secondary-300 dark:border-primary-700">
                        <p class="text-secondary-500 dark:text-secondary-400">No hay staff registrado.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <script>
        function togglePermission(userId, permissionName, isChecked) {
            // Update UI text immediately for better feedback
            const label = event.target.parentElement.querySelector('span');
            if(label) label.textContent = isChecked ? 'Permitido' : 'Denegado';

            fetch(`/admin/staff/${userId}/toggle-permission`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    permission: permissionName,
                    value: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                if(!data.success) {
                    alert('Error al actualizar permiso');
                    event.target.checked = !isChecked; // Revert
                    if(label) label.textContent = !isChecked ? 'Permitido' : 'Denegado';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión');
                event.target.checked = !isChecked; // Revert
            });
        }
    </script>
</x-app-layout>
