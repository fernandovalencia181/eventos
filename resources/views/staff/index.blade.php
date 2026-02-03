@extends('staff.layout')

@section('title', 'Dashboard Staff')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-900 dark:text-white">Panel de Control Staff</h1>
        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Gestión y control de eventos en tiempo real</p>
    </div>

    <!-- Tarjetas de acceso rápido -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Scanner -->
        <a href="{{ route('staff.scanner') }}" class="bg-white dark:bg-primary-900 rounded-lg shadow-md hover:shadow-xl transition-all p-6 border border-secondary-200 dark:border-primary-800 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-blue-600 dark:text-blue-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-2">Scanner QR</h3>
            <p class="text-sm text-secondary-600 dark:text-secondary-400">Validar entradas escaneando códigos</p>
        </a>

        <!-- Aforo -->
        <a href="{{ route('staff.aforo') }}" class="bg-white dark:bg-primary-900 rounded-lg shadow-md hover:shadow-xl transition-all p-6 border border-secondary-200 dark:border-primary-800 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-800/50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-green-600 dark:text-green-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-2">Control de Aforo</h3>
            <p class="text-sm text-secondary-600 dark:text-secondary-400">Monitorear capacidad de eventos</p>
        </a>

        <!-- Invitados -->
        <a href="{{ route('staff.invitados') }}" class="bg-white dark:bg-primary-900 rounded-lg shadow-md hover:shadow-xl transition-all p-6 border border-secondary-200 dark:border-primary-800 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-800/50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-purple-600 dark:text-purple-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-2">Lista de Invitados</h3>
            <p class="text-sm text-secondary-600 dark:text-secondary-400">Gestionar acceso VIP</p>
        </a>

        <!-- Incidencias -->
        <a href="{{ route('staff.incidencias') }}" class="bg-white dark:bg-primary-900 rounded-lg shadow-md hover:shadow-xl transition-all p-6 border border-secondary-200 dark:border-primary-800 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center group-hover:bg-red-200 dark:group-hover:bg-red-800/50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-red-600 dark:text-red-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-secondary-900 dark:text-white mb-2">Incidencias</h3>
            <p class="text-sm text-secondary-600 dark:text-secondary-400">Reportar problemas</p>
        </a>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-1">Validaciones Hoy</p>
                    <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $stats['validadas_hoy'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-blue-600 dark:text-blue-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-1">Eventos Activos</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['eventos_activos'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-green-600 dark:text-green-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-1">Incidencias Abiertas</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['incidencias'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-red-600 dark:text-red-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad reciente -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800">
        <h2 class="text-xl font-bold text-secondary-900 dark:text-white mb-4">Actividad Reciente</h2>
        
        <div class="space-y-4">
            @forelse($ultimas_validaciones as $val)
                <div class="flex items-center justify-between border-b border-secondary-200 dark:border-primary-800 pb-2 last:border-0 last:pb-0">
                    <div>
                        <p class="font-medium text-secondary-900 dark:text-white">
                            {{ $val->ticket->user->name ?? 'Invitado' }}
                        </p>
                        <p class="text-sm text-secondary-500 dark:text-secondary-400">
                            {{ $val->evento->nombre ?? 'Evento' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            {{ $val->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center text-secondary-600 dark:text-secondary-400 py-8">
                    No hay actividad reciente
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
