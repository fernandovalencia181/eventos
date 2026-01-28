<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <!-- VISTA DE ESCRITORIO (Tabla) -->
            <div class="hidden md:block bg-white overflow-hidden shadow-xl sm:rounded-lg border border-secondary-200">
                <table class="min-w-full divide-y divide-secondary-200">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-secondary-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-secondary-200">
                        @forelse ($users as $user)
                        <tr class="hover:bg-secondary-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    @if($user->phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="text-green-600 hover:text-green-800 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                            {{ $user->phone }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($user->rol === 'admin') bg-red-100 text-red-800 
                                    @elseif($user->rol === 'staff') bg-blue-100 text-blue-800 
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($user->rol) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4">
                    {{ $users->links() }}
                </div>
            </div>

            <!-- VISTA DE MÓVIL (Tarjetas) -->
            <div class="md:hidden space-y-4">
                @forelse ($users as $user)
                    <div class="bg-white rounded-xl shadow-md p-5 border border-secondary-100">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-secondary-900 text-lg leading-tight">{{ $user->name }}</h3>
                                <p class="text-sm text-secondary-500">{{ $user->email }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-bold rounded-full 
                                @if($user->rol === 'admin') bg-red-100 text-red-800 
                                @elseif($user->rol === 'staff') bg-blue-100 text-blue-800 
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($user->rol) }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            @if($user->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="flex items-center text-sm text-green-600 font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    Chat en WhatsApp
                                </a>
                            @else
                                <span class="text-sm text-secondary-400 italic">Sin teléfono registrado</span>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-secondary-100">
                            <a href="{{ route('users.edit', $user) }}" class="flex justify-center items-center w-full py-2 bg-indigo-50 border border-indigo-200 rounded-lg text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Editar Usuario
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 bg-white rounded-lg border border-dashed border-secondary-300">
                        <p class="text-secondary-500">No hay usuarios registrados.</p>
                    </div>
                @endforelse
                
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
