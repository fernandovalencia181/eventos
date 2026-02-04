<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Pase de Invitado</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- QRCode Lib -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-indigo-600 p-6 text-center">
            <h1 class="text-white text-2xl font-bold uppercase tracking-wider">Pase de Acceso</h1>
            <p class="text-indigo-200 text-sm mt-1">{{ $ticket->evento->nombre ?? 'Evento' }}</p>
        </div>

        <div class="p-8 flex flex-col items-center">
            
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $ticket->nombre_asistente }}</h2>
                <div class="text-sm text-gray-500 uppercase tracking-widest mt-1">Invitado</div>
            </div>

            <!-- QR Container -->
            <div class="relative group">
                <canvas id="qr-canvas" class="rounded-lg shadow-lg"></canvas>
                
                <!-- Overlay de carga -->
                <div id="loader" class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 flex items-center justify-center hidden">
                    <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Timer / Status -->
            <div class="mt-8 text-center w-full">
                <div class="flex justify-between text-xs text-gray-400 mb-1">
                    <span>Actualización automática</span>
                    <span id="timer-text">30s</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700 overflow-hidden">
                    <div id="progress-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-1000 ease-linear" style="width: 100%"></div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg text-xs text-yellow-800 dark:text-yellow-200 text-center">
                <p class="font-bold mb-1">⚠️ IMPORTANTE</p>
                Este código QR cambia automáticamente cada minuto por seguridad. 
                <br>No hagas capturas de pantalla, no funcionarán.
            </div>
            
            <p class="mt-6 text-xs text-center text-gray-400">
                Dispositivo Vinculado Seguro<br>
                ID: {{ substr($ticket->dispositivo_invitado, 0, 8) }}...
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = "{{ $ticket->token_invitado }}"; // Token de acceso URL
            const apiUrl = "{{ route('guest.qr_api', ['token' => 'TOKEN_PLACEHOLDER']) }}".replace('TOKEN_PLACEHOLDER', token);
            let currentQrToken = "{{ $ticket->token_reentrada ?? $ticket->token_seguridad_qr }}"; // Valor inicial
            
            const canvas = document.getElementById('qr-canvas');
            const timerText = document.getElementById('timer-text');
            const progressBar = document.getElementById('progress-bar');
            
            let timeLeft = 30;
            const refreshInterval = 30; // segundos

            function generateQR(text) {
                QRCode.toCanvas(canvas, text, { 
                    width: 250,
                    margin: 2,
                    color: {
                        dark: "#000000",
                        light: "#ffffff"
                    }
                }, function (error) {
                    if (error) console.error(error);
                });
            }

            // Generar inicial
            generateQR(currentQrToken);

            async function refreshToken() {
                try {
                    const response = await fetch(apiUrl);
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();
                    
                    if (data.qr_token) {
                        currentQrToken = data.qr_token;
                        generateQR(currentQrToken);
                        timeLeft = refreshInterval;
                    }
                } catch (error) {
                    console.error('Error fetching new token:', error);
                }
            }

            // Loop del timer
            setInterval(() => {
                timeLeft--;
                
                // Actualizar UI
                timerText.innerText = timeLeft + 's';
                progressBar.style.width = ((timeLeft / refreshInterval) * 100) + '%';

                if (timeLeft <= 0) {
                    refreshToken();
                }
            }, 1000);
            
            // Si el token inicial estaba vacío o expirado, refrescar inmediatamente
            @if(!$ticket->token_reentrada || $ticket->token_reentrada_expira < now())
                refreshToken();
            @endif
        });
    </script>
</body>
</html>
