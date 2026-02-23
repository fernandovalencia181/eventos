<?php
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Auth;

// CONTROLADORES
use App\Http\Controllers\EventoController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminStaffController;

// LIVEWIRE
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\MisEntradas;
use App\Livewire\Calendario;

// =========================================================================
// 🌍 ZONA PÚBLICA (Lo que ve todo el mundo sin loguearse)
// =========================================================================

// Rutas de Login con Google
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// Página de Inicio
Route::get('/', [EventoController::class, 'index'])->name('home');

// --- 2. ZONA DE REDIRECCIÓN (Login exitoso) ---
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// SISTEMA DE REGISTRO DE ENTRADAS (PÚBLICO)
Route::get('/registro/{evento}', [RegistrationController::class, 'create'])->name('registro.create');
Route::post('/registro/{evento}', [RegistrationController::class, 'store'])->name('registro.store');
Route::get('/registro/descargar/{registration}', [RegistrationController::class, 'download'])->name('registro.download');



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
    // Ver inscritos
    Route::get('/eventos/{evento}/inscritos', [EventoController::class, 'users'])->name('eventos.users');

    // Calendario (Ahora solo para admins)
    Route::get('/admin/calendario', Calendario::class)->name('calendario');

    // Gestión de Usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/crear', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/editar', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Gestión de Roles
    Route::resource('roles', RoleController::class);

    // Gestión de Equipo Staff (Cuentas)
    Route::post('admin/staff/{staff}/toggle-permission', [AdminStaffController::class, 'togglePermission'])->name('admin.staff.toggle-permission');
    Route::resource('admin/staff', AdminStaffController::class, ['as' => 'admin']);

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
    Route::get('/mi-pase', \App\Livewire\MiPase::class)->name('mi.pase'); // Nueva ruta para el pase dinámico

    // Calendario movido a Admin
    
    // Reserva de entradas (POST desde el Lobby)
    Route::post('/eventos/{evento}/reservar', [TicketController::class, 'store'])->name('eventos.reservar');
    
    // Descarga de PDF
    Route::get('/ticket/{ticket}/descargar', [PdfController::class, 'descargar'])
        ->name('ticket.descargar');

    // Rutas para el Usuario Propietario (Sistema Invitados y Dinámico)
    Route::post('/tickets/{ticket}/share', [TicketController::class, 'createGuestLink'])->name('tickets.share');
    Route::post('/tickets/{ticket}/dynamic-qr', [TicketController::class, 'getDynamicQr'])->name('tickets.dynamic_qr');
});


// ---> ZONA COMPAÑERO 3 (Staff y Scanner)
Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('index');
    Route::get('/scanner', [StaffController::class, 'scanner'])->name('scanner');
    Route::get('/validacion', [StaffController::class, 'validacion'])->name('validacion');
    Route::get('/asistencia', [StaffController::class, 'asistencia'])->name('asistencia');
    Route::get('/invitados', App\Livewire\Staff\Invitados::class)->name('invitados');
    Route::get('/incidencias', [StaffController::class, 'incidencias'])->name('incidencias');
    
    // AJAX y acciones
    Route::post('/validar', [StaffController::class, 'validar'])->name('validar');
    Route::post('/buscar', [StaffController::class, 'buscar'])->name('buscar');
    Route::post('/validar-manual', [StaffController::class, 'validarManual'])->name('validar-manual');
    Route::post('/registrar-incidencia', [StaffController::class, 'registrarIncidencia'])->name('registrar-incidencia');
    Route::post('/registrar-invitado', [StaffController::class, 'registrarInvitado'])->name('registrar-invitado');
    Route::post('/emitir-constancia', [StaffController::class, 'emitirConstancia'])->name('emitir-constancia');
    Route::get('/exportar', [StaffController::class, 'exportar'])->name('exportar');
});

// ZONA INVITADOS (Público pero con Token)
Route::get('/pase', [TicketController::class, 'guestView'])->name('guest.pass');
Route::get('/api/guest/qr/{token}', [TicketController::class, 'guestDynamicQr'])->name('guest.qr_api');
