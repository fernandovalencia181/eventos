@extends('staff.layout')

@section('title', 'Escàner QR')

@section('content')
<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6 sm:mb-8 gap-4">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
                    Escáner QR
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Validación rápida de entradas mediante códigos QR.
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0 w-full md:w-auto">
                <span class="inline-flex rounded-md shadow-sm w-full md:w-auto">
                    <button type="button" onclick="location.reload()" class="w-full md:w-auto justify-center inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-4 py-3 md:py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h-2.433a.75.75 0 000 1.5h3.989a.75.75 0 00.53-.22l.5-.5a6.375 6.375 0 009.466-2.26.75.75 0 00-1.252-.756l-.868.513c-.235.138-.517.07-.674-.162l-.248-.42z" clip-rule="evenodd" />
                        </svg>
                        Refrescar
                    </button>
                </span>
            </div>
        </div>

        <!-- Selector de Evento -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4 border border-gray-200 dark:border-gray-700">
            <label for="evento_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white mb-2">Evento Activo</label>
            <select id="evento_id" class="block w-full rounded-md border-0 py-3 pl-3 pr-10 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-900 text-base">
                <option value="">Selecciona un evento...</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Escáner -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <div id="reader" class="w-full rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-900 mb-4 min-h-[250px] sm:min-h-[300px]" style="display:none;"></div>
                
                <button id="toggleCamera" onclick="toggleScanner()" class="w-full flex items-center justify-center gap-2 rounded-md bg-primary-600 px-3 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 active:bg-primary-700">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                    </svg>
                    <span>Iniciar Cámara</span>
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white dark:bg-gray-800 px-2 text-sm text-gray-500">o validación manual</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" 
                           id="codigo_manual" 
                           class="block w-full rounded-md border-0 py-3 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-900 text-base" 
                           placeholder="Código de ticket...">
                    <button onclick="validarManual()" class="w-full sm:w-auto justify-center inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Validar
                    </button>
                </div>
            </div>

            <!-- Resultados y Estadísticas -->
            <div class="space-y-6">
                <!-- Resultado Validación -->
                <div id="resultado" class="hidden rounded-lg p-4 animate-fade-in-down transition-all duration-300"></div>

                <!-- Contadores -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 border border-l-4 border-green-500 shadow sm:p-6">
                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Validados</dt>
                        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white" id="validados">0</dd>
                    </div>
                    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 border border-l-4 border-red-500 shadow sm:p-6">
                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Incidencias</dt>
                        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white" id="duplicados">0</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    // Variables globales
    var html5QrCode = null;
    var scannerActivo = false;
    var validados = 0;
    var duplicados = 0;
    var isProcessing = false; // Prevents double submissions

    // Función para iniciar/detener escáner
    function toggleScanner() {
        console.log('toggleScanner llamado, scannerActivo:', scannerActivo);
        if (scannerActivo) {
            detenerScanner();
        } else {
            iniciarScanner();
        }
    }

    // Función para validación manual
    function validarManual() {
        var codigo = document.getElementById('codigo_manual').value.trim();
        console.log('validarManual llamado, codigo:', codigo);
        if (!codigo) return;
        
        validarCodigo(codigo);
        document.getElementById('codigo_manual').value = '';
    }

    function iniciarScanner() {
        console.log('Iniciando scanner...');
        var reader = document.getElementById('reader');
        reader.style.display = 'block';
        
        // Crear nueva instancia
        if (html5QrCode === null) {
            html5QrCode = new Html5Qrcode("reader");
        }
        
        var config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 }
        };
        
        html5QrCode.start(
            { facingMode: "environment" },
            config,
            function(decodedText) {
                console.log('QR detectado:', decodedText);
                validarCodigo(decodedText);
            },
            function(errorMessage) {
                // Ignorar errores de frame
            }
        ).then(function() {
            console.log('Scanner iniciado correctamente');
            scannerActivo = true;
            var btn = document.getElementById('toggleCamera');
            btn.innerHTML = '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg><span>Detener Cámara</span>';
            btn.className = 'w-full flex items-center justify-center gap-2 rounded-md bg-red-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors';
        }).catch(function(err) {
            console.error('Error iniciando scanner:', err);
            alert('Error al acceder a la cámara: ' + err);
            reader.style.display = 'none';
        });
    }

    function detenerScanner() {
        console.log('Deteniendo scanner...');
        if (html5QrCode && scannerActivo) {
            html5QrCode.stop().then(function() {
                console.log('Scanner detenido');
                scannerActivo = false;
                document.getElementById('reader').style.display = 'none';
                var btn = document.getElementById('toggleCamera');
                btn.innerHTML = '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg><span>Iniciar Cámara</span>';
                btn.className = 'w-full flex items-center justify-center gap-2 rounded-md bg-primary-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors';
            }).catch(function(err) {
                console.error('Error deteniendo scanner:', err);
            });
        }
    }

    function validarCodigo(codigo) {
        if (isProcessing) return; // Prevent spamming
        
        var eventoId = document.getElementById('evento_id').value;
        console.log('Validando codigo:', codigo, 'evento:', eventoId);

        if (!eventoId) {
            mostrarError('⚠️ Selecciona un evento primero');
            return;
        }

        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token no encontrado');
            mostrarError('Error: CSRF token no encontrado');
            return;
        }

        isProcessing = true;
        // Pause scanner momentarily if active to prevent multiple reads
        if(scannerActivo && html5QrCode) {
            html5QrCode.pause();
        }

        fetch('{{ route("staff.validar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', // Force JSON response expectation
                'X-CSRF-TOKEN': csrfToken.content
            },
            body: JSON.stringify({
                codigo: codigo.trim(),
                evento_id: eventoId
            })
        })
        .then(function(response) {
            // Check if response is OK (200-299)
            if (!response.ok) {
                return response.text().then(text => {
                    try {
                        const json = JSON.parse(text);
                        throw new Error(json.mensaje || 'Error del servidor (' + response.status + ')');
                    } catch (e) {
                         // If not JSON, it's likely an HTML error page
                        console.error('Error HTML:', text);
                        throw new Error('Error del servidor (' + response.status + '). Revisa la consola.');
                    }
                });
            }
            return response.json();
        })
        .then(function(data) {
            console.log('Respuesta:', data);
            if (data.success) {
                mostrarExito(data.mensaje, codigo);
                validados++;
                document.getElementById('validados').textContent = validados;
            } else {
                mostrarError(data.mensaje, codigo);
                duplicados++;
                document.getElementById('duplicados').textContent = duplicados;
            }
        })
        .catch(function(error) {
            console.error('Error fetch:', error);
            mostrarError(error.message || 'Error de conexión');
        })
        .finally(function() {
            // Retrasar la reactivación del escáner para evitar lecturas múltiples y dar tiempo a ver el mensaje
            if(scannerActivo && html5QrCode) {
                setTimeout(() => {
                    isProcessing = false;
                    try {
                        // Verificar que sigue activo antes de reanudar
                        if(scannerActivo) { 
                            html5QrCode.resume(); 
                            console.log('Scanner reanudado');
                        }
                    } catch (e) {
                        console.log('El scanner ya no estaba pausado o activo');
                    }
                }, 2500); // 2.5 segundos de pausa entre escaneos
            } else {
                isProcessing = false;
            }
        });
    }

    function mostrarExito(mensaje, codigo) {
        var resultado = document.getElementById('resultado');
        resultado.className = 'rounded-lg p-4 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 mb-6';
        resultado.innerHTML = '<div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg></div><div class="ml-3"><h3 class="text-sm font-medium text-green-800 dark:text-green-200">' + mensaje + '</h3><div class="mt-2 text-sm text-green-700 dark:text-green-300"><p>Codi: ' + codigo + '</p></div></div></div>';
        resultado.classList.remove('hidden');
        setTimeout(function() { resultado.classList.add('hidden'); }, 3000);
    }

    function mostrarError(mensaje, codigo) {
        var resultado = document.getElementById('resultado');
        resultado.className = 'rounded-lg p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 mb-6';
        var html = '<div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></div><div class="ml-3"><h3 class="text-sm font-medium text-red-800 dark:text-red-200">' + mensaje + '</h3>';
        if (codigo) {
            html += '<div class="mt-2 text-sm text-red-700 dark:text-red-300"><p>Codi: ' + codigo + '</p></div>';
        }
        html += '</div></div>';
        resultado.innerHTML = html;
        resultado.classList.remove('hidden');
        setTimeout(function() { resultado.classList.add('hidden'); }, 5000);
    }

    // Event listener para Enter en el input
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('codigo_manual').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                validarManual();
            }
        });
        console.log('Scanner page loaded');
    });
</script>
@endpush
