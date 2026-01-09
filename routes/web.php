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

// --- 1. ZONA PÚBLICA ---
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
    
    // Aquí irán futuras rutas de admin (editar, borrar, escanear, etc.)
});


// --- 4. ZONA USUARIO AUTENTICADO (Ajustes de perfil) ---
// Estas son las rutas que traía Jetstream para cambiar clave, foto, etc.
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
    Route::get('settings/two-factor', TwoFactor::class)->name('two-factor.show');
    // 1. Mostrar el formulario de edición (recibe el ID del evento)
    Route::get('/eventos/{evento}/editar', [EventoController::class, 'edit'])->name('eventos.edit');
    // 2. Guardar los cambios (PUT es el verbo para actualizar)
    Route::put('/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    // 3. Eliminar el evento (DELETE)
    Route::delete('/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');
});