<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$asistencias = App\Models\Asistencia::with(['ticket.user', 'guest'])->get();

echo "Total Asistencias Raw: " . $asistencias->count() . "\n";

foreach ($asistencias as $a) {
    echo "ID: " . $a->id . " | ";
    if ($a->ticket && $a->ticket->user) {
        echo "USER: " . $a->ticket->user->name . " (" . $a->ticket->user->rol . ") | ";
    } else {
        echo "USER: NULL | ";
    }
    
    if ($a->guest) {
        echo "GUEST: YES";
    } else {
        echo "GUEST: NO";
    }
    echo "\n";
}

echo "\n--- FILTER CHECK ---\n";
// Apply the filter logic exactly as in controller
$query = App\Models\Asistencia::query();
$query->where(function($q) {
   $q->whereHas('ticket', function($qt) {
       $qt->whereHas('user', function($u) {
           $u->whereNotIn('rol', ['admin', 'staff']);
       });
   })
   ->orWhereHas('guest');
});

$filtered = $query->get();
echo "Filtered Count: " . $filtered->count() . "\n";
foreach ($filtered as $a) {
    echo "ID: " . $a->id . "\n";
}
