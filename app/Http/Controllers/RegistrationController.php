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

class RegistrationController extends Controller
{
    public function create(Evento $evento)
    {
        return view('registrations.create', compact('evento'));
    }

    public function store(Request $request, Evento $evento)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'estudios' => 'required|string',
            'guests' => 'nullable|array|max:3',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.phone' => 'nullable|string|max:20', // Validar telefono
        ]);

        // Verificar si ya existe registro con ese email para este evento
        if (Registration::where('event_id', $evento->id)->where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Ya existe una entrada registrada con este email para este evento.']);
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

            // 3. Generar y Descargar PDF (Con QR en Base64)
            return $this->generatePdfResponse($registration, $evento);

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
        
        // Si no está logueado, podríamos permitirlo si conoce la URL, pero mejor restringir o dejar abierto si es público.
        // Dado el requerimiento "acceder alli para descargar", asumimos contexto seguro.
        
        $registration->load('guests', 'event');
        return $this->generatePdfResponse($registration, $registration->event);
    }

    private function generatePdfResponse(Registration $registration, Evento $evento)
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
        
        return $pdf->download('entrada-' . Str::slug($evento->nombre) . '-' . $registration->id . '.pdf');
    }
}
