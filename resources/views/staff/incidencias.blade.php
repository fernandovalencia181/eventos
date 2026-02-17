@extends('staff.layout')

@section('title', 'Gestión de Incidencias')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header con gradiente -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-primary-900 dark:text-white">Gestión de Incidencias</h1>
            </div>
            <p class="text-secondary-600 dark:text-secondary-400">Reportar y gestionar problemas durante los eventos</p>
        </div>
        <button onclick="document.getElementById('modal_incidencia').classList.remove('hidden')"
            class="bg-gradient-to-r from-red-600 to-red-500 text-white px-6 py-3 rounded-xl hover:from-red-700 hover:to-red-600 transition-all shadow-lg shadow-red-500/25 inline-flex items-center gap-2 font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Reportar Incidencia
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-5 py-4 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Estadísticas con diseño moderno -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pendientes -->
        <div class="relative overflow-hidden bg-white dark:bg-primary-900 rounded-2xl shadow-xl border border-secondary-100 dark:border-primary-800 p-6 group hover:shadow-2xl transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/10 to-orange-500/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Pendientes</span>
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                </div>
                <p class="text-5xl font-black text-secondary-900 dark:text-white mb-1">{{ $stats['pendientes'] }}</p>
                <p class="text-sm text-red-500 dark:text-red-400 font-medium flex items-center gap-1">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    Requieren atención
                </p>
            </div>
        </div>

        <!-- En Proceso -->
        <div class="relative overflow-hidden bg-white dark:bg-primary-900 rounded-2xl shadow-xl border border-secondary-100 dark:border-primary-800 p-6 group hover:shadow-2xl transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-500/10 to-yellow-500/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">En Proceso</span>
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/25 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </div>
                </div>
                <p class="text-5xl font-black text-secondary-900 dark:text-white mb-1">{{ $stats['en_proceso'] }}</p>
                <p class="text-sm text-amber-500 dark:text-amber-400 font-medium flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 animate-spin">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                    </svg>
                    Siendo atendidas
                </p>
            </div>
        </div>

        <!-- Resueltas -->
        <div class="relative overflow-hidden bg-white dark:bg-primary-900 rounded-2xl shadow-xl border border-secondary-100 dark:border-primary-800 p-6 group hover:shadow-2xl transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-green-500/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">Resueltas</span>
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/25 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-5xl font-black text-secondary-900 dark:text-white mb-1">{{ $stats['resueltas'] }}</p>
                <p class="text-sm text-emerald-500 dark:text-emerald-400 font-medium flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Completadas
                </p>
            </div>
        </div>
    </div>

    <!-- Filtros con diseño moderno -->
    <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-lg p-5 border border-secondary-100 dark:border-primary-800 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                </svg>
            </div>
            <span class="font-semibold text-secondary-900 dark:text-white">Filtrar incidencias</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select id="filtro_estado" class="bg-secondary-50 dark:bg-primary-800 border-0 rounded-xl px-4 py-3 text-secondary-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition">
                <option value="">Estado: Todos</option>
                <option value="pendiente">🔴 Pendiente</option>
                <option value="en_proceso">🟡 En proceso</option>
                <option value="resuelta">🟢 Resuelta</option>
            </select>
            <select id="filtro_prioridad" class="bg-secondary-50 dark:bg-primary-800 border-0 rounded-xl px-4 py-3 text-secondary-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition">
                <option value="">Prioridad: Todas</option>
                <option value="critica">⚫ Crítica</option>
                <option value="alta">🔴 Alta</option>
                <option value="media">🟠 Media</option>
                <option value="baja">🔵 Baja</option>
            </select>
            <select id="filtro_evento" class="bg-secondary-50 dark:bg-primary-800 border-0 rounded-xl px-4 py-3 text-secondary-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition">
                <option value="">Evento: Todos</option>
                @foreach($eventos as $evento)
                <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Lista de incidencias con diseño mejorado -->
    <div class="space-y-4" id="lista-incidencias">
        @forelse($incidencias as $incidencia)
        <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-lg border border-secondary-100 dark:border-primary-800 p-6 hover:shadow-xl transition-all duration-300 group" 
             data-estado="{{ $incidencia->estado }}" 
             data-prioridad="{{ $incidencia->prioridad }}" 
             data-evento="{{ $incidencia->evento_id }}">
            <div class="flex items-start gap-4">
                <!-- Icono del tipo -->
                <div class="flex-shrink-0">
                    @if($incidencia->tipo === 'qr_perdido' || $incidencia->tipo === 'perdida_qr')
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/25 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                        </svg>
                    </div>
                    @elseif($incidencia->tipo === 'error_datos')
                    <div class="w-14 h-14 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg shadow-rose-500/25 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    @elseif($incidencia->tipo === 'acceso_denegado')
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-700 rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/25 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    @elseif($incidencia->tipo === 'tecnico')
                    <div class="w-14 h-14 bg-gradient-to-br from-slate-500 to-slate-700 rounded-2xl flex items-center justify-center shadow-lg shadow-slate-500/25 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                        </svg>
                    </div>
                    @elseif($incidencia->tipo === 'doble_entrada')
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/25 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                        </svg>
                    </div>
                    @else
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-yellow-500/25 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Contenido -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <h3 class="text-lg font-bold text-secondary-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $incidencia->tipo)) }}</h3>
                        
                        <!-- Badge Prioridad -->
                        <span class="px-3 py-1 text-xs font-bold rounded-full inline-flex items-center gap-1
                            {{ $incidencia->prioridad === 'critica' ? 'bg-black text-white' :
                               ($incidencia->prioridad === 'alta' ? 'bg-gradient-to-r from-red-500 to-rose-500 text-white' : 
                               ($incidencia->prioridad === 'media' ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white' : 
                               'bg-gradient-to-r from-blue-500 to-indigo-500 text-white')) }}">
                            @if($incidencia->prioridad === 'critica')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            @endif
                            {{ ucfirst($incidencia->prioridad) }}
                        </span>
                        
                        <!-- Badge Estado -->
                        <span class="px-3 py-1 text-xs font-bold rounded-full inline-flex items-center gap-1
                            {{ $incidencia->estado === 'resuelta' ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white' : 
                               ($incidencia->estado === 'en_proceso' ? 'bg-gradient-to-r from-amber-400 to-yellow-500 text-white' : 
                               'bg-gradient-to-r from-red-500 to-rose-500 text-white') }}">
                            @if($incidencia->estado === 'resuelta')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            @elseif($incidencia->estado === 'en_proceso')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 animate-spin">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            @else
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $incidencia->estado)) }}
                        </span>
                    </div>
                    
                    <p class="text-secondary-700 dark:text-secondary-300 mb-4 leading-relaxed">{{ $incidencia->descripcion }}</p>
                    
                    <!-- Metadatos -->
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <span class="inline-flex items-center gap-2 text-secondary-500 dark:text-secondary-400 bg-secondary-100 dark:bg-primary-800 px-3 py-1.5 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            {{ $incidencia->created_at->format('d/m/Y H:i') }}
                        </span>
                        <span class="inline-flex items-center gap-2 text-secondary-500 dark:text-secondary-400 bg-secondary-100 dark:bg-primary-800 px-3 py-1.5 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            {{ $incidencia->staff->nombre ?? 'Staff' }}
                        </span>
                        <span class="inline-flex items-center gap-2 text-secondary-500 dark:text-secondary-400 bg-secondary-100 dark:bg-primary-800 px-3 py-1.5 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                            </svg>
                            {{ $incidencia->evento->nombre }}
                        </span>
                        @if($incidencia->fecha_resolucion)
                        <span class="inline-flex items-center gap-2 text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-3 py-1.5 rounded-lg font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Resuelta: {{ $incidencia->fecha_resolucion->format('d/m/Y H:i') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-xl border border-secondary-100 dark:border-primary-800 p-16 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-500/25">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
            </div>
            <p class="text-xl font-bold text-secondary-900 dark:text-white mb-2">¡Excelente!</p>
            <p class="text-secondary-600 dark:text-secondary-400">No hay incidencias registradas</p>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal Nueva Incidencia con diseño mejorado -->
<div id="modal_incidencia" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-primary-900 rounded-2xl max-w-lg w-full p-8 shadow-2xl transform transition-all">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-secondary-900 dark:text-white">Reportar Incidencia</h3>
                <p class="text-sm text-secondary-500 dark:text-secondary-400">Describe el problema con detalle</p>
            </div>
        </div>
        <form method="POST" action="{{ route('staff.registrar-incidencia') }}">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-secondary-900 dark:text-white mb-2">Evento</label>
                    <select name="evento_id" required class="w-full bg-secondary-50 dark:bg-primary-800 border-0 rounded-xl px-4 py-3 text-secondary-900 dark:text-white focus:ring-2 focus:ring-red-500 transition">
                        <option value="">Seleccionar evento...</option>
                        @foreach($eventos as $evento)
                        <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-secondary-900 dark:text-white mb-2">Tipo de Incidencia</label>
                    <select name="tipo" required class="w-full bg-secondary-50 dark:bg-primary-800 border-0 rounded-xl px-4 py-3 text-secondary-900 dark:text-white focus:ring-2 focus:ring-red-500 transition">
                        <option value="perdida_qr">📱 QR Perdido</option>
                        <option value="error_datos">📄 Error en Datos</option>
                        <option value="doble_entrada">🔄 Doble Entrada</option>
                        <option value="otro">⚠️ Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-secondary-900 dark:text-white mb-2">Descripción</label>
                    <textarea name="descripcion" required rows="4" placeholder="Describe el problema con detalle..." class="w-full bg-secondary-50 dark:bg-primary-800 border-0 rounded-xl px-4 py-3 text-secondary-900 dark:text-white focus:ring-2 focus:ring-red-500 transition resize-none"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal_incidencia').classList.add('hidden')" class="flex-1 bg-secondary-200 dark:bg-primary-800 text-secondary-700 dark:text-white py-3.5 rounded-xl hover:bg-secondary-300 dark:hover:bg-primary-700 transition font-semibold">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-500 text-white py-3.5 rounded-xl hover:from-red-700 hover:to-red-600 transition font-semibold shadow-lg shadow-red-500/25 inline-flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        Reportar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Filtros en tiempo real
    const filtroEstado = document.getElementById('filtro_estado');
    const filtroPrioridad = document.getElementById('filtro_prioridad');
    const filtroEvento = document.getElementById('filtro_evento');

    function aplicarFiltros() {
        const estado = filtroEstado.value;
        const prioridad = filtroPrioridad.value;
        const evento = filtroEvento.value;

        const incidencias = document.querySelectorAll('#lista-incidencias > div');

        incidencias.forEach(inc => {
            const incEstado = inc.getAttribute('data-estado');
            const incPrioridad = inc.getAttribute('data-prioridad');
            const incEvento = inc.getAttribute('data-evento');

            let mostrar = true;

            if (estado && incEstado !== estado) mostrar = false;
            if (prioridad && incPrioridad !== prioridad) mostrar = false;
            if (evento && incEvento !== evento) mostrar = false;

            inc.style.display = mostrar ? '' : 'none';
        });
    }

    filtroEstado.addEventListener('change', aplicarFiltros);
    filtroPrioridad.addEventListener('change', aplicarFiltros);
    filtroEvento.addEventListener('change', aplicarFiltros);
</script>
@endpush
