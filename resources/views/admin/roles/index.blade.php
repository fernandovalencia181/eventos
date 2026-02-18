<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Gestión de Roles
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Cabecera Estilo Staff -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="relative w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                        <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                        <div class="relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Roles y Permisos</h1>
                        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Definición de niveles de acceso</p>
                    </div>
                </div>

                <a href="{{ route('roles.create') }}" class="w-full md:w-auto bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-purple-700 hover:to-indigo-700 shadow-lg transform hover:scale-105 transition font-bold flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m12-3c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8z" /></svg>
                    Nuevo Rol
                </a>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl relative shadow-sm">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl relative shadow-sm">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-primary-900 overflow-hidden shadow-xl sm:rounded-xl border border-secondary-200 dark:border-primary-800 p-1">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-secondary-200 dark:border-primary-800">
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Etiqueta</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Identificador</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Permisos</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100 dark:divide-primary-800">
                        @foreach ($roles as $role)
                        <tr class="hover:bg-secondary-50 dark:hover:bg-primary-800/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-secondary-900 dark:text-white">{{ $role->label }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500 dark:text-secondary-400">
                                <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-xs font-mono">{{ $role->name }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500 dark:text-secondary-400">
                                @if(isset($role->permissions['access_guests']) && $role->permissions['access_guests'])
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-800">
                                        Gestión Invitados
                                    </span>
                                @else
                                    <span class="text-xs text-secondary-400 bg-secondary-100 dark:bg-primary-800 px-2 py-1 rounded-full">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('roles.edit', $role) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3 font-bold transition-colors">Editar</a>
                                @if($role->name !== 'admin' && $role->name !== 'user' && $role->name !== 'staff')
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Borrar rol?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-bold transition-colors">Borrar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
