@extends('staff.layout')

@section('title', 'Escàner QR')

@push('styles')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .dark .glass-card {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="relative w-20 h-20 bg-gradient-to-br from-indigo-400 via-purple-500 to-pink-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-6 transition-all duration-300">
                <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                <div class="relative z-10">
                    <!-- Icono QR personalizado estético -->
                    <svg viewBox="0 0 40 40" class="w-10 h-10" fill="none">
                        <rect x="4" y="4" width="12" height="12" rx="2" stroke="white" stroke-width="2.5" fill="white" opacity="0.9"/>
                        <rect x="24" y="4" width="12" height="12" rx="2" stroke="white" stroke-width="2.5" fill="white" opacity="0.9"/>
                        <rect x="4" y="24" width="12" height="12" rx="2" stroke="white" stroke-width="2.5" fill="white" opacity="0.9"/>
                        <circle cx="10" cy="10" r="3" fill="#6366f1"/>
                        <circle cx="30" cy="10" r="3" fill="#a855f7"/>
                        <circle cx="10" cy="30" r="3" fill="#ec4899"/>
                        <rect x="24" y="24" width="4" height="4" rx="1" fill="white"/>
                        <rect x="30" y="24" width="4" height="4" rx="1" fill="white"/>
                        <rect x="24" y="30" width="4" height="4" rx="1" fill="white"/>
                        <rect x="30" y="30" width="4" height="4" rx="1" fill="white"/>
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-4xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Escàner QR</h1>
                <p class="text-secondary-600 dark:text-secondary-400 mt-1">Validació ràpida d'entrades amb codi QR</p>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-2xl shadow-2xl p-8 mb-6">
        <!-- Seleccionar Esdeveniment -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-secondary-900 dark:text-white mb-2 inline-flex items-center gap-2">
                <svg viewBox="0 0 16 16" class="w-4 h-4" fill="currentColor">
                    <path d="M3 2a1 1 0 011-1h8a1 1 0 011 1v1h1a1 1 0 011 1v10a1 1 0 01-1 1H2a1 1 0 01-1-1V4a1 1 0 011-1h1V2z"/>
                    <rect x="5" y="6" width="6" height="1.5" rx="0.5" fill="white"/>
                    <rect x="5" y="9" width="4" height="1.5" rx="0.5" fill="white"/>
                </svg>
                Esdeveniment
            </label>
            <select id="evento_id" class="w-full bg-secondary-50 dark:bg-primary-800 border border-secondary-300 dark:border-primary-700 rounded-lg px-4 py-2 text-secondary-900 dark:text-white">
                <option value="">Selecciona un esdeveniment...</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Escàner -->
    <div class="glass-card rounded-2xl shadow-2xl p-8 mb-6">
        <button id="toggleCamera" onclick="toggleScanner()" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-5 rounded-xl mb-6 hover:from-indigo-700 hover:to-purple-700 text-lg font-bold transition-all transform hover:scale-[1.02] shadow-lg">
            <span class="flex items-center justify-center gap-3">
                <svg viewBox="0 0 24 24" class="w-7 h-7" fill="none">
                    <rect x="3" y="6" width="18" height="14" rx="2" stroke="white" stroke-width="2"/>
                    <circle cx="12" cy="13" r="3.5" stroke="white" stroke-width="2" fill="white" opacity="0.3"/>
                    <circle cx="12" cy="13" r="2" fill="white"/>
                    <path d="M8 6L9.5 3.5C9.5 3.5 10.5 2 12 2C13.5 2 14.5 3.5 14.5 3.5L16 6" stroke="white" stroke-width="2"/>
                    <circle cx="18" cy="9" r="1.5" fill="#FFD700"/>
                </svg>
                Iniciar Càmera
            </span>
        </button>

        <div id="reader" class="mb-6 rounded-xl overflow-hidden shadow-inner" style="display:none;"></div>

        <div class="border-t border-white/10 pt-6">
            <p class="text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-3 inline-flex items-center gap-2">
                <svg viewBox="0 0 16 16" class="w-4 h-4" fill="currentColor">
                    <circle cx="8" cy="8" r="7" fill="#FFD700" opacity="0.3"/>
                    <path d="M8 3v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                O introduïx el codi manualment:
            </p>
            <div class="flex gap-3">
                <input type="text" 
                       id="codigo_manual" 
                       class="flex-1 bg-white/50 dark:bg-black/30 border border-indigo-300 dark:border-indigo-700 rounded-xl px-5 py-4 text-secondary-900 dark:text-white placeholder-secondary-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" 
                       placeholder="Introdu\u00efx el codi del tiquet...">
                <button onclick="validarManual()" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-10 py-4 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all font-bold shadow-lg transform hover:scale-105 inline-flex items-center gap-2">
                    <svg viewBox="0 0 16 16" class="w-5 h-5" fill="white">
                        <circle cx="8" cy="8" r="7" stroke="white" stroke-width="2" fill="none"/>
                        <path d="M5 8l2 2 4-4" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                    </svg>
                    Validar
                </button>
            </div>
        </div>
    </div>

    <!-- Resultado -->
    <div id="resultado" class="hidden"></div>

    <!-- Comptador -->
    <div class="grid grid-cols-2 gap-6">
        <div class="relative overflow-hidden bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-2xl p-8 text-center transform hover:scale-105 transition-all">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
            <div class="relative z-10">
                <p class="text-sm text-green-100 font-medium mb-2 inline-flex items-center gap-2 mx-auto">
                    <svg viewBox="0 0 16 16" class="w-4 h-4" fill="white">
                        <circle cx="8" cy="8" r="7"/>
                        <path d="M5 8l2 2 4-4" stroke="#10b981" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                    VALIDATS
                </p>
                <p class="text-6xl font-black text-white drop-shadow-lg" id="validados">0</p>
            </div>
        </div>
        <div class="relative overflow-hidden bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-2xl p-8 text-center transform hover:scale-105 transition-all">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
            <div class="relative z-10">
                <p class="text-sm text-red-100 font-medium mb-2 inline-flex items-center gap-2 mx-auto">
                    <svg viewBox="0 0 16 16" class="w-4 h-4" fill="white">
                        <circle cx="8" cy="8" r="7"/>
                        <path d="M5 5l6 6M11 5l-6 6" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    DUPLICATS
                </p>
                <p class="text-6xl font-black text-white drop-shadow-lg" id="duplicados">0</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
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
        html5QrCode = new Html5Qrcode("reader");
        
        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };
        
        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText) => {
                // Callback quan s'escaneja correctament
                validarCodigo(decodedText);
            }
        ).then(() => {
            scannerActivo = true;
            const btn = document.getElementById('toggleCamera');
            btn.innerHTML = `
                <span class="flex items-center justify-center gap-3">
                    <svg viewBox="0 0 24 24" class="w-7 h-7" fill="none">
                        <rect x="6" y="6" width="12" height="12" rx="2" stroke="white" stroke-width="2.5"/>
                    </svg>
                    Aturar Càmera
                </span>
            `;
            btn.className = 'w-full bg-gradient-to-r from-red-600 to-rose-600 text-white py-5 rounded-xl mb-6 hover:from-red-700 hover:to-rose-700 text-lg font-bold transition-all transform hover:scale-[1.02] shadow-lg';
        }).catch(err => {
            alert('⚠️ Error en accedir a la càmera.\n\nAssegura\'t de:\n1. Donar permisos de càmera al navegador\n2. Utilitzar HTTPS o localhost\n3. Tenir una càmera connectada\n\nError: ' + err);
            console.error('Error scanner:', err);
            document.getElementById('reader').style.display = 'none';
        });
    }

    function detenerScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                scannerActivo = false;
                document.getElementById('reader').style.display = 'none';
                const btn = document.getElementById('toggleCamera');
                btn.innerHTML = `
                    <span class="flex items-center justify-center gap-3">
                        <svg viewBox="0 0 24 24" class="w-7 h-7" fill="none">
                            <rect x="3" y="6" width="18" height="14" rx="2" stroke="white" stroke-width="2"/>
                            <circle cx="12" cy="13" r="3.5" stroke="white" stroke-width="2" fill="white" opacity="0.3"/>
                            <circle cx="12" cy="13" r="2" fill="white"/>
                            <path d="M8 6L9.5 3.5C9.5 3.5 10.5 2 12 2C13.5 2 14.5 3.5 14.5 3.5L16 6" stroke="white" stroke-width="2"/>
                            <circle cx="18" cy="9" r="1.5" fill="#FFD700"/>
                        </svg>
                        Iniciar Càmera
                    </span>
                `;
                btn.className = 'w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-5 rounded-xl mb-6 hover:from-indigo-700 hover:to-purple-700 text-lg font-bold transition-all transform hover:scale-[1.02] shadow-lg';
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
            const resultado = document.getElementById('resultado');
            resultado.classList.remove('hidden');
            resultado.innerHTML = `
                <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-400 px-6 py-4 rounded-lg mb-4">
                    <h3 class="font-bold text-lg">⚠️ Selecciona un esdeveniment</h3>
                </div>
            `;
            setTimeout(() => resultado.classList.add('hidden'), 3000);
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
                    codigo: codigo,
                    evento_id: eventoId
                })
            });

            const data = await response.json();

            const resultado = document.getElementById('resultado');
            resultado.classList.remove('hidden');

            if (data.success) {
                validados++;
                document.getElementById('validados').textContent = validados;
                
                // Efecto de sonido de éxito (opcional)
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIE13s2N+Mxx8DRpvf8sFwIwUr');
                audio.play().catch(() => {});
                
                resultado.innerHTML = `
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-8 py-6 rounded-2xl mb-6 shadow-2xl transform animate-bounce">
                        <div class="flex items-center gap-4">
                            <div class="text-5xl">✅</div>
                            <div>
                                <h3 class="font-black text-2xl mb-1">${data.mensaje}</h3>
                                <p class="text-green-100 font-mono text-sm">Codi: ${codigo}</p>
                            </div>
                        </div>
                    </div>
                `;

                setTimeout(() => {
                    resultado.classList.add('hidden');
                }, 3000);
            } else {
                duplicados++;
                document.getElementById('duplicados').textContent = duplicados;

                resultado.innerHTML = `
                    <div class="bg-gradient-to-r from-red-500 to-rose-500 text-white px-8 py-6 rounded-2xl mb-6 shadow-2xl transform animate-shake">
                        <div class="flex items-center gap-4">
                            <div class="text-5xl">❌</div>
                            <div>
                                <h3 class="font-black text-2xl mb-1">${data.mensaje}</h3>
                                <p class="text-red-100 font-mono text-sm">Codi: ${codigo}</p>
                            </div>
                        </div>
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
                    <h3 class="font-bold text-lg">❌ Error de connexió</h3>
                    <p class="text-sm mt-1">No s'ha pogut connectar amb el servidor. Comprova la connexió.</p>
                </div>
            `;
        }
    }
</script>
@endpush
