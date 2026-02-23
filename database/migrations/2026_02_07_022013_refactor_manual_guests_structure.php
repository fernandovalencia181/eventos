<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modificar tabla 'guests' para permitir entradas manuales (sin registro web asociado)
        Schema::table('guests', function (Blueprint $table) {
            $table->unsignedBigInteger('registration_id')->nullable()->change();
            $table->string('email')->nullable()->after('name'); // Añadir email
            $table->string('company')->nullable()->after('name'); // Añadir empresa/cargo si se quiere, o lo dejamos en notas
        });

        // 2. Migrar datos de 'invitados_especiales' a 'guests'
        // Esto preserva los datos antes de borrar la tabla antigua
        $legacies = DB::table('invitados_especiales')->get();
        
        foreach($legacies as $legacy) {
            // Buscar si ya existe ticket para obtener el token, o generar uno nuevo
            // Como Guests usa qr_token como clave unica logicamente para el ticket
            
            // Intentamos buscar un ticket que coincida con este invitado especial (por evento y nombre)
            // Si el invitado especial ya se había procesado en un Ticket, usamos ese token.
            $existingTicket = DB::table('entradas')
                                ->where('evento_id', $legacy->evento_id)
                                ->where('nombre_asistente', $legacy->nombre)
                                ->first();

            $token = $existingTicket ? $existingTicket->token_seguridad_qr : Str::random(32);

            // Crear el registro en guests
            DB::table('guests')->insert([
                'registration_id' => null, // Es manual
                'name' => $legacy->nombre,
                'email' => $legacy->email,
                'phone' => $legacy->telefono,
                'qr_token' => $token,
                'created_at' => $legacy->created_at,
                'updated_at' => $legacy->updated_at,
            ]);
        }

        // 3. Borrar tabla antigua
        Schema::dropIfExists('invitados_especiales');

        // 4. Borrar columna telefono de entradas (ya que ahora se leerá de guests o users)
        if (Schema::hasColumn('entradas', 'telefono')) {
            Schema::table('entradas', function (Blueprint $table) {
                $table->dropColumn('telefono');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios (aproximado)
        Schema::table('entradas', function (Blueprint $table) {
            $table->string('telefono')->nullable();
        });

        // Recrear tabla (vacía)
        Schema::create('invitados_especiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id');
            $table->string('nombre');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();
        });

        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('company');
            // No podemos hacer registration_id not null fácilmente porque ahora hay nulls
        });
    }
};
