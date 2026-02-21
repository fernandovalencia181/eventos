<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('evento_id')->constrained()->onDelete('cascade');
            $table->string('folio')->unique(); // Código único del ticket
            $table->string('qr_code')->nullable(); // Ruta del QR generado
            $table->enum('estado', ['activo', 'usado', 'cancelado'])->default('activo');
            $table->timestamp('fecha_compra')->useCurrent();
            $table->timestamp('fecha_uso')->nullable(); // Cuándo se validó
            $table->foreignId('validado_por')->nullable()->constrained('users'); // Staff que validó
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index(['evento_id', 'estado']);
            $table->index('folio');
        });

        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('evento_id')->constrained()->onDelete('cascade');
            $table->timestamp('hora_entrada')->useCurrent();
            $table->timestamp('hora_salida')->nullable();
            $table->foreignId('validado_por')->constrained('users'); // Staff que registró
            $table->text('notas')->nullable(); // Observaciones del staff
            $table->timestamps();
            
            $table->index(['evento_id', 'created_at']);
        });
        */

        // Tabla para espacios/salas si el evento tiene subdivisiones
        Schema::create('espacios_evento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained()->onDelete('cascade');
            $table->string('nombre'); // Ej: "Sala A", "Taller 1"
            $table->integer('capacidad')->default(0);
            $table->integer('ocupacion_actual')->default(0);
            $table->timestamps();
        });

        // Asignación de usuarios a espacios
        Schema::create('asignaciones_espacio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('espacio_id')->constrained('espacios_evento')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('asignado_por')->constrained('users');
            $table->timestamps();
            
            $table->unique(['espacio_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_espacio');
        Schema::dropIfExists('espacios_evento');
        /*
        Schema::dropIfExists('asistencias');
        Schema::dropIfExists('tickets');
        */
    }
};
