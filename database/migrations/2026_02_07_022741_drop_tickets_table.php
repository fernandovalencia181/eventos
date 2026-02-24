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
        // Primero eliminar tablas dependientes o claves foráneas
        Schema::dropIfExists('asistencias');
        Schema::dropIfExists('incidencias'); // Esta también solía tener relación con tickets
        
        // Finalmente eliminar la tabla tickets
        Schema::dropIfExists('tickets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed as we are cleaning up a redundant table
    }
};
