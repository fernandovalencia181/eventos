<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// IMPORTACIONES DE LIVEWIRE (Lo que ya venía)
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;

// IMPORTACIONES DE TU EQUIPO (NUEVO)
use App\Http\Controllers\EventoController;
use App\Livewire\MisEntradas; 
use App\Livewire\Calendario;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\TicketController;
// use App\Http\Controllers\StaffController;  // Descomentar cuando tu compa cree el suyo

// =========================================================================
// 🌍 ZONA PÚBLICA (Lo que ve todo el mundo sin loguearse)
// =========================================================================

// Cambiamos la función anónima por TU controlador para mostrar los eventos reales
Route::get('/', [EventoController::class, 'index'])->name('home');


// =========================================================================
// 🔒 ZONA PRIVADA (Dashboard y Configuración - Lo que trajo Laravel)
// =========================================================================

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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

// ZONA FERNANDO (Gestión de Eventos - Solo Admin/Auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/eventos/crear', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
});


// ---> ZONA COMPAÑERO 2 (Tickets y Registro - Público o Auth)
// Ejemplo: Route::get('/registro/{id}', [TicketController::class, 'create']);
Route::middleware([
    'auth', // <--- Usa la autenticación web estándar de Laravel
    'verified' // Opcional: Solo si requieres verificar email
])->group(function () {
    Route::get('/mis-entradas', MisEntradas::class)->name('mis.entradas');
    Route::get('/calendario', Calendario::class)->name('calendario');
    Route::post('/eventos/{evento}/reservar', [TicketController::class, 'store'])->name('eventos.reservar');
    Route::get('/ticket/{ticket}/descargar', [PdfController::class, 'descargar'])
        ->name('ticket.descargar');
});


// ---> ZONA COMPAÑERO 3 (Staff y Scanner)
// Ejemplo: Route::get('/scanner', [StaffController::class, 'index']);