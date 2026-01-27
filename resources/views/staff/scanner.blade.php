@extends('staff.layout')

@section('title', 'Escàner QR')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
                    Escàner QR
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Validació ràpida d'entrades mitjançant codis QR.
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <span class="inline-flex rounded-md shadow-sm">
                    <button type="button" onclick="location.reload()" class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
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
            <label for="evento_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white mb-2">Esdeveniment Actiu</label>
            <select id="evento_id" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-900">
                <option value="">Selecciona un esdeveniment...</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            <!-- Escáner -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                <div id="reader" class="w-full rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-900 mb-4 min-h-[300px]" style="display:none;"></div>
                
                <button id="toggleCamera" onclick="toggleScanner()" class="w-full flex items-center justify-center gap-2 rounded-md bg-primary-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                    </svg>
                    <span>Iniciar Càmera</span>
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white dark:bg-gray-800 px-2 text-sm text-gray-500">o validació manual</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <input type="text" 
                           id="codigo_manual" 
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-900" 
                           placeholder="Codi de tiquet...">
                    <button onclick="validarManual()" class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
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
                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Validats</dt>
                        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white" id="validados">0</dd>
                    </div>
                    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 border border-l-4 border-red-500 shadow sm:p-6">
                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Incidències</dt>
                        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white" id="duplicados">0</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
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
        document.getElementById('reader').style.display = 'block';
        
        // Si ya existe una instancia, la usamos
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }
        
        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };
        
        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText) => {
                // Prevenir lecturas múltiples muy seguidas si se desea, por ahora validamos directo
                validarCodigo(decodedText);
            }
        ).then(() => {
            scannerActivo = true;
            const btn = document.getElementById('toggleCamera');
            btn.innerHTML = `
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Aturar Càmera</span>
            `;
            btn.className = 'w-full flex items-center justify-center gap-2 rounded-md bg-red-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600';
        }).catch(err => {
            alert('⚠️ Error en accedir a la càmera.\n\nAssegura\'t de:\n1. Donar permisos de càmera al navegador\n2. Utilitzar HTTPS o localhost\n3. Tenir una càmera connectada');
            console.error('Error scanner:', err);
            document.getElementById('reader').style.display = 'none';
        });
    }

    function detenerScanner() {
        if (html5QrCode && scannerActivo) {
            html5QrCode.stop().then(() => {
                scannerActivo = false;
                document.getElementById('reader').style.display = 'none';
                const btn = document.getElementById('toggleCamera');
                btn.innerHTML = `
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                    </svg>
                    <span>Iniciar Càmera</span>
                `;
                btn.className = 'w-full flex items-center justify-center gap-2 rounded-md bg-primary-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600';
            }).catch(err => {
                console.error('Error aturant scanner:', err);
            });
        }
    }

    function validarManual() {
        const codigo = document.getElementById('codigo_manual').value.trim();
        if (!codigo) return;
        
        validarCodigo(codigo);
        document.getElementById('codigo_manual').value = '';
    }

    document.getElementById('codigo_manual').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') validarManual();
    });

    async function validarCodigo(codigo) {
        const eventoId = document.getElementById('evento_id').value;

        if (!eventoId) {
            mostrarError('⚠️ Selecciona un esdeveniment primer');
            return;
        }

        try {
            const response = await fetch('{{ route("staff.validar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    codigo: codigo.trim(),
                    evento_id: eventoId
                })
            });

            const data = await response.json();

            if (data.success) {
                mostrarExito(data.mensaje, codigo);
                validados++;
                document.getElementById('validados').textContent = validados;
                
                // So d'èxit
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIE13s2N+Mxx8DRpvf8sFwIwUr');
                audio.play().catch(() => {});
            } else {
                mostrarError(data.mensaje, codigo);
                duplicados++;
                document.getElementById('duplicados').textContent = duplicados;
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarError('❌ Error de connexió');
        }
    }

    function mostrarExito(mensaje, codigo) {
        const resultado = document.getElementById('resultado');
        resultado.className = 'rounded-lg p-4 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 animate-fade-in-down mb-6';
        resultado.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-200">${mensaje}</h3>
                    <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                        <p>Codi: ${codigo}</p>
                    </div>
                </div>
            </div>
        `;
        resultado.classList.remove('hidden');
        setTimeout(() => resultado.classList.add('hidden'), 3000);
    }

    function mostrarError(mensaje, codigo = '') {
        const resultado = document.getElementById('resultado');
        resultado.className = 'rounded-lg p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 animate-fade-in-down mb-6';
        resultado.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">${mensaje}</h3>
                    ${codigo ? `<div class="mt-2 text-sm text-red-700 dark:text-red-300"><p>Codi: ${codigo}</p></div>` : ''}
                </div>
            </div>
        `;
        resultado.classList.remove('hidden');
        setTimeout(() => resultado.classList.add('hidden'), 5000);
    }
</script>
@endsection
