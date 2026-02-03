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
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();

            // Relación con entradas (que usa UUID es importante que coincida el tipo)
            $table->uuid('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('entradas')->onDelete('cascade');

            $table->string('guest_qr_token')->nullable();

            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');

            $table->timestamp('fecha_checkin')->useCurrent();
            $table->string('metodo')->default('qr'); // ej: 'qr', 'manual', 'dni'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
