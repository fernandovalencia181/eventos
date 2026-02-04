<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Usuario afectado, opcional
            
            // Ticket es UUID
            $table->uuid('ticket_id')->nullable();
            $table->foreign('ticket_id')->references('id')->on('entradas')->onDelete('set null');
            
            $table->string('tipo'); // ej: 'medico', 'seguridad', 'tecnico'
            $table->text('descripcion');
            $table->string('estado')->default('pendiente'); // 'pendiente', 'en_proceso', 'resuelto'
            
            $table->foreignId('reportado_por')->nullable()->constrained('users'); // Staff que reporta
            $table->foreignId('resuelto_por')->nullable()->constrained('users'); // Staff que resuelve
            
            $table->timestamp('fecha_resolucion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
