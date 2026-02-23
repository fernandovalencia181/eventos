<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'adminr@gmail.com')->first();
if ($user) {
    // Check Asistencia for this user (via ticket)
    $tickets = App\Models\Ticket::where('user_id', $user->id)->pluck('id');
    $asistencias = App\Models\Asistencia::whereIn('ticket_id', $tickets)->get();
    
    foreach ($asistencias as $asistencia) {
        echo "Asistencia ID: " . $asistencia->id . "\n";
        echo "Ticket ID: " . $asistencia->ticket_id . "\n";
        echo "Guest Token: |" . $asistencia->guest_qr_token . "|\n";
        
        // Does guest exist?
        if ($asistencia->guest) {
             echo "HAS GUEST: YES (ID: " . $asistencia->guest->id . ")\n";
        } else {
             echo "HAS GUEST: NO\n";
        }
    }
} else {
    echo "USER_NOT_FOUND\n";
}
