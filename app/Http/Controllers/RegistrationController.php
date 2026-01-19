<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Registration;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
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
                'name' => $request->name,
                'email' => $request->email,
                'course' => $request->estudios,
                'qr_token' => (string) Str::uuid(),
            ]);

            // 2. Crear Acompañantes
            if ($request->has('guests')) {
                foreach ($request->guests as $guestData) {
                    Guest::create([
                        'registration_id' => $registration->id,
                        'name' => $guestData['name'],
                        'qr_token' => (string) Str::uuid(),
                    ]);
                }
            }

            DB::commit();

            // 3. Generar y Descargar PDF
            // Cargar la relación para la vista PDF
            $registration->load('guests', 'event');

            $pdf = Pdf::loadView('registrations.pdf', compact('registration'));
            
            // Forzar descarga con nombre personalizado
            return $pdf->download('entrada-' . Str::slug($evento->nombre) . '-' . $registration->id . '.pdf');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al procesar tu registro. Por favor, inténtalo de nuevo. ' . $e->getMessage()]);
        }
    }
}
