<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold mb-2">Panel de Staff</h1>
            <p class="text-gray-600">Escáner de entradas para eventos</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <a href="{{ route('staff.scanner') }}" class="block bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg p-12 hover:shadow-2xl transition text-center">
                <div class="text-8xl mb-6">📱</div>
                <h2 class="font-bold text-4xl mb-3">Iniciar Scanner</h2>
                <p class="text-blue-100 text-lg">Escanear códigos QR para validar tickets</p>
            </a>
        </div>
    </div>

</body>
</html>
