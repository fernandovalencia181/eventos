<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 2. Crear tabla ENTRADAS
        Schema::create('entradas', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relación con users
            
            $table->string('nombre_asistente')->nullable();
            $table->enum('estado', ['generada', 'adentro', 'afuera', 'anulada'])->default('generada');
            $table->string('token_seguridad_qr');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};