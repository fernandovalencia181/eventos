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
    
    // Aquí irán futuras rutas de admin (editar, borrar, escanear, etc.)
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
    Route::get('/calendario', Calendario::class)->name('calendario');
    
    // Reserva de entradas (POST desde el Lobby)
    Route::post('/eventos/{evento}/reservar', [TicketController::class, 'store'])->name('eventos.reservar');
    
    // Descarga de PDF
    Route::get('/ticket/{ticket}/descargar', [PdfController::class, 'descargar'])
        ->name('ticket.descargar');
});


// ---> ZONA COMPAÑERO 3 (Staff y Scanner)
// Ejemplo: Route::get('/scanner', [StaffController::class, 'index']);