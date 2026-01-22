@extends('staff.layout')

@section('title', 'Scanner QR')

@section('head')
<script src="https://unpkg.com/html5-qrcode"></script>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-900 dark:text-white">📱 Scanner de Entradas</h1>
        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Escanea códigos QR para validar entradas</p>
    </div>

    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6 mb-6">
        <!-- Seleccionar Evento -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Evento</label>
            <select id="evento_id" class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                <option value="">Selecciona un evento...</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </div>

        <!-- Seleccionar Staff -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2">Tu perfil</label>
            <select id="staff_id" class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                <option value="">Selecciona tu nombre...</option>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}">{{ $s->nombre }} - {{ $s->rol }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Scanner -->
    <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-6 mb-6">
        <button id="toggleCamera" onclick="toggleScanner()" class="w-full bg-primary-600 text-white py-4 rounded-lg mb-4 hover:bg-primary-700 text-lg font-bold transition">
            Iniciar Cámara
        </button>

        <div id="reader" class="mb-4" style="display:none;"></div>

        <div class="border-t border-secondary-200 dark:border-primary-800 pt-4">
            <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-2">O ingresa el código manualmente:</p>
            <div class="flex gap-3">
                <input type="text" 
                       id="codigo_manual" 
                       class="flex-1 bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-3 text-secondary-900 dark:text-white" 
                       placeholder="Código del ticket...">
                <button onclick="validarManual()" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition">
                    Validar
                </button>
            </div>
        </div>
    </div>

    <!-- Resultado -->
    <div id="resultado" class="hidden"></div>

    <!-- Contador -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-6 text-center">
            <p class="text-sm text-green-600 dark:text-green-400 mb-2">✅ Validados</p>
            <p class="text-5xl font-bold text-green-700 dark:text-green-500" id="validados">0</p>
        </div>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-6 text-center">
            <p class="text-sm text-red-600 dark:text-red-400 mb-2">❌ Duplicados</p>
            <p class="text-5xl font-bold text-red-700 dark:text-red-500" id="duplicados">0</p>
        </div>
    </div>

</div>
@endsection

@section('scripts')
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
            document.getElementById('toggleCamera').classList.remove('bg-primary-600', 'hover:bg-primary-700');
            document.getElementById('toggleCamera').classList.add('bg-red-600', 'hover:bg-red-700');
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
                document.getElementById('toggleCamera').classList.remove('bg-red-600', 'hover:bg-red-700');
                document.getElementById('toggleCamera').classList.add('bg-primary-600', 'hover:bg-primary-700');
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

        try {
            const response = await fetch('{{ route("staff.validar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    codigo: codigo,
                    staff_id: staffId,
                    evento_id: eventoId
                })
            });

            const data = await response.json();

            const resultado = document.getElementById('resultado');
            resultado.classList.remove('hidden');

            if (data.success) {
                validados++;
                document.getElementById('validados').textContent = validados;
                
                resultado.innerHTML = `
                    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-400 px-6 py-4 rounded-lg mb-4">
                        <h3 class="font-bold text-lg mb-2">✅ ${data.mensaje}</h3>
                        <p class="text-sm">Código: <span class="font-mono">${codigo}</span></p>
                    </div>
                `;

                setTimeout(() => {
                    resultado.classList.add('hidden');
                }, 3000);
            } else {
                duplicados++;
                document.getElementById('duplicados').textContent = duplicados;

                resultado.innerHTML = `
                    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 px-6 py-4 rounded-lg mb-4">
                        <h3 class="font-bold text-lg mb-2">${data.mensaje}</h3>
                        <p class="text-sm">Código: <span class="font-mono">${codigo}</span></p>
                    </div>
                `;

                setTimeout(() => {
                    resultado.classList.add('hidden');
                }, 5000);
            }
        } catch (error) {
            console.error('Error:', error);
            const resultado = document.getElementById('resultado');
            resultado.classList.remove('hidden');
            resultado.innerHTML = `
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 px-6 py-4 rounded-lg mb-4">
                    <h3 class="font-bold text-lg">❌ Error de conexión</h3>
                </div>
            `;
        }
    }
</script>
@endsection
