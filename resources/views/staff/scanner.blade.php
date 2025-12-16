<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner QR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen">

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        
        <div class="bg-gray-800 rounded-lg p-6 mb-6">
            <h1 class="text-3xl font-bold mb-4">📱 Scanner de Entradas</h1>
            
            <!-- Seleccionar Evento -->
            <div class="mb-4">
                <label class="block text-sm mb-2">Evento</label>
                <select id="evento_id" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2">
                    <option value="">Selecciona un evento...</option>
                    @foreach($eventos as $evento)
                        <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Seleccionar Staff -->
            <div class="mb-4">
                <label class="block text-sm mb-2">Tu perfil</label>
                <select id="staff_id" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2">
                    <option value="">Selecciona tu nombre...</option>
                    @foreach($staff as $s)
                        <option value="{{ $s->id }}">{{ $s->nombre }} - {{ $s->rol }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Scanner -->
        <div class="bg-gray-800 rounded-lg p-6 mb-6">
            <button id="toggleCamera" onclick="toggleScanner()" class="w-full bg-blue-600 text-white py-4 rounded-lg mb-4 hover:bg-blue-700 text-lg font-bold">
                Iniciar Cámara
            </button>

            <div id="reader" class="mb-4" style="display:none;"></div>

            <div class="border-t border-gray-700 pt-4">
                <p class="text-sm text-gray-400 mb-2">O ingresa el código manualmente:</p>
                <div class="flex gap-3">
                    <input type="text" 
                           id="codigo_manual" 
                           class="flex-1 bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white" 
                           placeholder="Código del ticket...">
                    <button onclick="validarManual()" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700">
                        Validar
                    </button>
                </div>
            </div>
        </div>

        <!-- Resultado -->
        <div id="resultado" class="hidden"></div>

        <!-- Contador -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-green-900/30 border border-green-700 rounded-lg p-6 text-center">
                <p class="text-sm text-green-300 mb-2">✅ Validados</p>
                <p class="text-5xl font-bold text-green-400" id="validados">0</p>
            </div>
            <div class="bg-red-900/30 border border-red-700 rounded-lg p-6 text-center">
                <p class="text-sm text-red-300 mb-2">❌ Duplicados</p>
                <p class="text-5xl font-bold text-red-400" id="duplicados">0</p>
            </div>
        </div>

    </div>

    <script>
        let html5QrCode = null;
        let scannerActivo = false;
        let validados = 0;
        let duplicados = 0;

        function toggleScanner() {
            if (scannerActivo) {
                detenerScanner();
            } else {
                iniciarScanner();
            }
        }

        function iniciarScanner() {
            if (!validarSelecciones()) return;

            document.getElementById('reader').style.display = 'block';
            html5QrCode = new Html5Qrcode("reader");
            
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (codigo) => validarCodigo(codigo)
            ).then(() => {
                scannerActivo = true;
                document.getElementById('toggleCamera').textContent = 'Detener Cámara';
                document.getElementById('toggleCamera').classList.remove('bg-blue-600');
                document.getElementById('toggleCamera').classList.add('bg-red-600');
            }).catch(err => {
                alert('Error al acceder a la cámara. Verifica los permisos.');
                console.error(err);
            });
        }

        function detenerScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    scannerActivo = false;
                    document.getElementById('reader').style.display = 'none';
                    document.getElementById('toggleCamera').textContent = 'Iniciar Cámara';
                    document.getElementById('toggleCamera').classList.remove('bg-red-600');
                    document.getElementById('toggleCamera').classList.add('bg-blue-600');
                });
            }
        }

        function validarManual() {
            const codigo = document.getElementById('codigo_manual').value.trim();
            if (!codigo) {
                alert('Ingresa un código');
                return;
            }
            validarCodigo(codigo);
            document.getElementById('codigo_manual').value = '';
        }

        document.getElementById('codigo_manual').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') validarManual();
        });

        function validarSelecciones() {
            const eventoId = document.getElementById('evento_id').value;
            const staffId = document.getElementById('staff_id').value;
            
            if (!eventoId) {
                alert('⚠️ Selecciona un evento');
                return false;
            }
            if (!staffId) {
                alert('⚠️ Selecciona tu perfil');
                return false;
            }
            return true;
        }

        async function validarCodigo(codigo) {
            if (!validarSelecciones()) return;

            const eventoId = document.getElementById('evento_id').value;
            const staffId = document.getElementById('staff_id').value;

            mostrarResultado('loading', '⏳ Validando...');

            try {
                const response = await fetch('{{ route("staff.validar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ codigo, staff_id: staffId, evento_id: eventoId })
                });

                const data = await response.json();

                if (data.success) {
                    mostrarResultado('success', data.mensaje, data.ticket);
                    validados++;
                    document.getElementById('validados').textContent = validados;
                } else {
                    mostrarResultado('error', data.mensaje);
                    duplicados++;
                    document.getElementById('duplicados').textContent = duplicados;
                }
            } catch (error) {
                mostrarResultado('error', '❌ Error de conexión');
                console.error(error);
            }
        }

        function mostrarResultado(tipo, mensaje, ticket = null) {
            const resultado = document.getElementById('resultado');
            resultado.classList.remove('hidden');

            let color = tipo === 'success' ? 'green' : tipo === 'loading' ? 'blue' : 'red';

            resultado.innerHTML = `
                <div class="bg-${color}-900/50 border-2 border-${color}-500 rounded-lg p-6 mb-6">
                    <p class="text-3xl font-bold text-${color}-300 mb-3">${mensaje}</p>
                    ${ticket ? `
                        <div class="text-sm text-${color}-200">
                            <p><strong>Código:</strong> ${ticket.codigo}</p>
                        </div>
                    ` : ''}
                </div>
            `;

            if (tipo !== 'loading') {
                setTimeout(() => resultado.classList.add('hidden'), 4000);
            }
        }
    </script>

</body>
</html>
