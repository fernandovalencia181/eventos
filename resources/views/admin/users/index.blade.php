<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Gestión de Usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cabecera Estilo Staff -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="relative w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                        <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                        <div class="relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">Directorio de Usuarios</h1>
                        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Administración global de cuentas</p>
                    </div>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <!-- FILTROS -->
            <div class="mb-8 bg-white dark:bg-primary-900 p-4 sm:p-6 rounded-xl shadow-lg border border-secondary-200 dark:border-primary-800">
                <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Buscador -->
                    <div class="md:col-span-3">
                        <label for="search" class="block text-xs font-bold text-secondary-500 dark:text-secondary-400 uppercase mb-2">Buscar</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, email o teléfono..." class="w-full pl-10 pr-4 py-3 border border-secondary-300 dark:border-primary-600 rounded-xl text-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-primary-800 dark:text-white dark:placeholder-secondary-500 shadow-sm transition-colors">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro Rol -->
                    <div>
                        <label for="rol" class="block text-xs font-bold text-secondary-500 dark:text-secondary-400 uppercase mb-2">Rol</label>
                        <div class="relative">
                            <select name="rol" class="w-full border border-secondary-300 dark:border-primary-600 rounded-xl text-sm py-3 px-4 focus:ring-pink-500 focus:border-pink-500 dark:bg-primary-800 dark:text-white shadow-sm appearance-none cursor-pointer hover:bg-secondary-50 dark:hover:bg-primary-700 transition-colors" onchange="this.form.submit()">
                                <option value="">Todos</option>
                                <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="staff" {{ request('rol') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="user" {{ request('rol') == 'user' ? 'selected' : '' }}>Usuario</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-secondary-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- VISTA DE ESCRITORIO (Tabla) -->
            <div class="hidden md:block bg-white dark:bg-primary-900 overflow-hidden shadow-xl sm:rounded-xl border border-secondary-200 dark:border-primary-800 p-1">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-secondary-200 dark:border-primary-800">
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Teléfono</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100 dark:divide-primary-800">
                        @forelse ($users as $user)
                        <tr class="hover:bg-secondary-50 dark:hover:bg-primary-800/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-secondary-900 dark:text-white">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-secondary-500 dark:text-secondary-400">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-secondary-500 dark:text-secondary-400">
                                    @if($user->phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 flex items-center gap-1 font-medium transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                            {{ $user->phone }}
                                        </a>
                                    @else
                                        <span class="text-secondary-300 dark:text-secondary-600">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border 
                                    @if($user->rol === 'admin') bg-red-100 text-red-800 border-red-200 dark:bg-red-900/40 dark:text-red-300 dark:border-red-800
                                    @elseif($user->rol === 'staff') bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-800
                                    @else bg-green-100 text-green-800 border-green-200 dark:bg-green-900/40 dark:text-green-300 dark:border-green-800 @endif">
                                    {{ ucfirst($user->rol) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('users.edit', $user) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3 font-bold transition-colors">Editar</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-secondary-500 dark:text-secondary-400 border-t border-secondary-100 dark:border-primary-800">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-secondary-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <span class="text-lg font-medium">No se encontraron usuarios</span>
                                    <p class="text-sm mt-1">Intenta con otros filtros de búsqueda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-secondary-200 dark:border-primary-800 bg-secondary-50 dark:bg-primary-900/50 rounded-b-xl">

```                    {{ $users->links() }}
                </div>
            </div>

            <!-- VISTA DE MÓVIL (Tarjetas) -->
            <div class="md:hidden space-y-4">
                @forelse ($users as $user)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-gray-100 text-lg leading-tight">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-bold rounded-full 
                                @if($user->rol === 'admin') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @elseif($user->rol === 'staff') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                {{ ucfirst($user->rol) }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            @if($user->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="flex items-center text-sm text-green-600 dark:text-green-400 font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    Chat en WhatsApp
                                </a>
                            @else
                                <span class="text-sm text-gray-400 dark:text-gray-500 italic">Sin teléfono registrado</span>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('users.edit', $user) }}" class="flex justify-center items-center w-full py-2 bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-800 rounded-lg text-sm font-semibold text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/70 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Editar Usuario
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 bg-white dark:bg-gray-800 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                        <p class="text-gray-500 dark:text-gray-400">No hay usuarios registrados.</p>
                    </div>
                @endforelse
                
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
