<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos U.edu.co</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-secondary-50 font-sans text-secondary-900 antialiased">

    <nav class="bg-white shadow-sm border-b border-secondary-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-primary-600">EventosU</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-secondary-600 hover:text-primary-600 font-medium">Login</a>
                    <a href="#" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-500 transition">Registrarse</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-secondary-900 tracking-tight">
                Próximos <span class="text-primary-600">Eventos</span>
            </h1>
            <p class="mt-4 text-xl text-secondary-500">Reserva tu entrada para las mejores conferencias y graduaciones.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @forelse($eventos as $evento)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-secondary-100">
                    <div class="h-48 bg-gradient-to-r from-primary-500 to-primary-700 flex items-center justify-center">
                        <span class="text-white text-4xl font-bold opacity-30">IMG</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold bg-primary-50 text-primary-700 px-2 py-1 rounded-full">
                                Aforo: {{ $evento->aforo_maximo }}
                            </span>
                            <span class="text-xs text-secondary-500">
                                {{ \Carbon\Carbon::parse($evento->fecha)->format('d M, Y') }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-secondary-900 mb-2">{{ $evento->nombre }}</h3>
                        <p class="text-secondary-500 text-sm mb-4">📍 {{ $evento->lugar }}</p>
                        
                        <button class="w-full py-2.5 bg-secondary-900 text-white font-medium rounded-xl hover:bg-primary-600 transition-colors">
                            Obtener Ticket
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-10">
                    <p class="text-secondary-500 text-lg">Aún no hay eventos programados.</p>
                </div>
            @endforelse

        </div>
    </main>

</body>
</html>