<?php
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Auth;
// CONTROLADORES
use App\Http\Controllers\EventoController;
// LIVEWIRE
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;

// IMPORTACIONES DE TU EQUIPO (NUEVO)
use App\Http\Controllers\TicketController; // Descomentar cuando tu compa cree el suyo
use App\Http\Controllers\PdfController; // Descomentar cuando tu compa cree el suyo
use App\Http\Controllers\StaffController; 
use App\Livewire\MisEntradas;
use App\Livewire\Calendario; 
// =========================================================================
// 🌍 ZONA PÚBLICA (Lo que ve todo el mundo sin loguearse)
// =========================================================================

// Cambiamos la función anónima por TU controlador para mostrar los eventos reales
Route::get('/', [EventoController::class, 'index'])->name('home');

// --- 2. ZONA DE REDIRECCIÓN (Login exitoso) ---
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */ // <--- ESTO LE DICE A VS CODE QUIÉN ES EL USUARIO
    $user = Auth::user();  

    if ($user->rol === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    
    if ($user->rol === 'staff') {
        return redirect()->route('staff.dashboard');
    }
    
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 3. ZONA ADMINISTRADOR (Protegida por middleware 'admin') ---
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // Panel Principal
    Route::get('/admin/dashboard', [EventoController::class, 'dashboard'])->name('admin.dashboard');
    
    // Gestión de Eventos
    Route::get('/eventos/crear', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{evento}/editar', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');

    // Calendario (Ahora solo para admins)
    Route::get('/admin/calendario', Calendario::class)->name('calendario');

    // Aquí irán futuras rutas de admin (editar, borrar, escanear, etc.)
});


// --- 5. ZONA STAFF (Protegida por middleware 'staff') ---
Route::middleware(['auth', 'verified', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard Staff
    Route::get('/dashboard', function () {
        $eventos = \App\Models\Evento::where('fecha', '>=', now())
            ->orderBy('fecha')
            ->get();
        return view('staff.dashboard', compact('eventos'));
    })->name('dashboard');
    
    // Gestión de Evento Específico
    Route::prefix('evento/{eventoId}')->name('evento.')->group(function () {
        
        // Check-in / Validación
        Route::get('/validacion', function ($eventoId) {
            $evento = \App\Models\Evento::findOrFail($eventoId);
            return view('staff.validacion', compact('evento', 'eventoId'));
        })->name('validacion');
        
        // Control de Aforo
        Route::get('/aforo', function ($eventoId) {
            $evento = \App\Models\Evento::findOrFail($eventoId);
            return view('staff.aforo', compact('evento', 'eventoId'));
        })->name('aforo');
        
        // Gestión de Incidencias
        Route::get('/incidencias', function ($eventoId) {
            $evento = \App\Models\Evento::findOrFail($eventoId);
            return view('staff.incidencias', compact('evento', 'eventoId'));
        })->name('incidencias');
        
        // Gestión de Invitados Especiales
        Route::get('/invitados', function ($eventoId) {
            $evento = \App\Models\Evento::findOrFail($eventoId);
            return view('staff.invitados', compact('evento', 'eventoId'));
        })->name('invitados');
    });
});


// --- 4. ZONA USUARIO AUTENTICADO (Ajustes de perfil) ---
// Estas son las rutas que traía Jetstream para cambiar clave, foto, etc.
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// =========================================================================
// 👷 ZONA DE EQUIPO (Agreguen sus rutas aquí abajo para no pisarse)
// =========================================================================

// (La gestión de eventos ya está arriba en la Zona Admin, eliminamos duplicados aquí)


// ---> ZONA COMPAÑERO 2 (Tickets y Registro - Público o Auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mis-entradas', MisEntradas::class)->name('mis.entradas');
    // Calendario movido a Admin
    
    // Reserva de entradas (POST desde el Lobby)
    Route::post('/eventos/{evento}/reservar', [TicketController::class, 'store'])->name('eventos.reservar');
    
    // Descarga de PDF
    Route::get('/ticket/{ticket}/descargar', [PdfController::class, 'descargar'])
        ->name('ticket.descargar');
});


// ---> ZONA COMPAÑERO 3 (Staff y Scanner)
// Ejemplo: Route::get('/scanner', [StaffController::class, 'index']);