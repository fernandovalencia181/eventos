<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Registration;
use App\Models\Guest;
use App\Models\Ticket; // Importante
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\EntranceMail;

class RegistrationController extends Controller
{
    public function create(Evento $evento)
    {
        // 1. Verificar si el usuario ya tiene entrada
        if (Auth::check()) {
            if (Registration::where('event_id', $evento->id)->where('user_id', Auth::id())->exists()) {
                return redirect()->route('mis.entradas')->with('info', '¡Ya tienes tu entrada! No necesitas registrarte de nuevo.');
            }
        }

        // 2. Verificar aforo
        if ($evento->lugares_disponibles <= 0) {
            return redirect()->route('home')->with('error', 'Lo sentimos, este evento ya ha completado su aforo.');
        }
        return view('registrations.create', compact('evento'));
    }

    public function store(Request $request, Evento $evento)
    {
        $maxGuests = $evento->max_guests ?? 0;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'estudios' => 'required|string',
            'guests' => 'nullable|array|max:' . $maxGuests,
            'guests.*.name' => 'required|string|max:255',
            'guests.*.phone' => 'nullable|string|max:20', 
        ], [
            'guests.max' => 'El número máximo de acompañantes permitidos es ' . $maxGuests . '.',
        ]);

        // 0. Validar Aforo Disponible
        $cantidadSolicitada = 1; // El titular cuenta como uno
        if ($request->has('guests')) {
            $cantidadSolicitada += count($request->guests);
        }

        if ($cantidadSolicitada > $evento->lugares_disponibles) {
            return back()->withErrors(['error' => 'No hay suficiente espacio. Solo quedan ' . $evento->lugares_disponibles . ' lugares disponibles.']);
        }

        // Verificar si ya existe registro con ese email para este evento
        if (Registration::where('event_id', $evento->id)->where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Ya existe una entrada registrada con este email para este evento.']);
        }

        // Verificar si el usuario autenticado ya tiene registro (doble seguridad)
        if (Auth::check() && Registration::where('event_id', $evento->id)->where('user_id', Auth::id())->exists()) {
            return redirect()->route('mis.entradas')->with('error', 'Ya tienes una entrada para este evento.');
        }

        try {
            DB::beginTransaction();

            // 1. Crear el Titular
            $registration = Registration::create([
                'event_id' => $evento->id,
                'user_id' => Auth::check() ? Auth::id() : null,
                'name' => $request->name,
                'email' => $request->email,
                'course' => $request->estudios,
                'qr_token' => (string) Str::uuid(),
            ]);

            // Sincronizar con tabla ENTRADAS (Ticket) para control de acceso
            Ticket::create([
                'evento_id' => $evento->id,
                'user_id' => Auth::check() ? Auth::id() : null,
                'nombre_asistente' => $request->name,
                'estado' => 'generada',
                'token_seguridad_qr' => $registration->qr_token,
            ]);

            // 2. Crear Acompañantes
            if ($request->has('guests')) {
                foreach ($request->guests as $guestData) {
                    $guest = Guest::create([
                        'registration_id' => $registration->id,
                        'name' => $guestData['name'],
                        'phone' => $guestData['phone'] ?? null,
                        'qr_token' => (string) Str::uuid(),
                    ]);

                    // Crear Ticket para el acompañante también
                    Ticket::create([
                        'evento_id' => $evento->id,
                        'user_id' => null, // Guest no tiene usuario propio aun
                        'nombre_asistente' => $guestData['name'],
                        'estado' => 'generada',
                        'token_seguridad_qr' => $guest->qr_token,
                    ]);
                }
            }

            DB::commit();

            // 3. Generar PDF
            $pdf = $this->preparePdf($registration, $evento);
            $output = $pdf->output();

            // 4. Enviar Email
            try {
                Mail::to($registration->email)->send(new EntranceMail($registration, $output));
            } catch (\Exception $e) {
                // Loguear error pero no detener la descarga
                \Illuminate\Support\Facades\Log::error('Error enviando email: ' . $e->getMessage());
            }

            return $pdf->download('entrada-' . Str::slug($evento->nombre) . '-' . $registration->id . '.pdf');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al procesar tu registro. Por favor, inténtalo de nuevo. ' . $e->getMessage()]);
        }
    }

    public function download(Registration $registration)
    {
        // Seguridad: Solo el dueño o el que tenga el email (implementación simple: si está logueado y es suyo)
        if (Auth::check() && $registration->user_id !== Auth::id()) {
            abort(403);
        }
        
        $registration->load('guests', 'event');
        $pdf = $this->preparePdf($registration, $registration->event);
        return $pdf->download('entrada-' . Str::slug($registration->event->nombre) . '-' . $registration->id . '.pdf');
    }

    private function preparePdf(Registration $registration, Evento $evento)
    {
        // Helper para obtener QR en base64
        $getQrBase64 = function($token) {
            $url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . $token;
            try {
                $image = file_get_contents($url);
                return 'data:image/png;base64,' . base64_encode($image);
            } catch (\Exception $e) {
                return null; // Fallback o handling
            }
        };

        // Generar QRs para titular y guests
        $registration->qr_image = $getQrBase64($registration->qr_token);
        foreach ($registration->guests as $guest) {
            $guest->qr_image = $getQrBase64($guest->qr_token);
        }

        $pdf = Pdf::loadView('registrations.pdf', compact('registration'));
        $pdf->setOption('isRemoteEnabled', true); // Backup
        
        return $pdf;
    }
}
