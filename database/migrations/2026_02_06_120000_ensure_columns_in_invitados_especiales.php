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
        Schema::table('invitados_especiales', function (Blueprint $table) {
            if (!Schema::hasColumn('invitados_especiales', 'estado')) {
                $table->string('estado')->default('pendiente')->after('nombre');
            }
            if (!Schema::hasColumn('invitados_especiales', 'email')) {
                $table->string('email')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('invitados_especiales', 'telefono')) {
                $table->string('telefono')->nullable()->after('email');
            }
            if (!Schema::hasColumn('invitados_especiales', 'cargo')) {
                $table->string('cargo')->nullable()->after('telefono');
            }
            if (!Schema::hasColumn('invitados_especiales', 'empresa')) {
                $table->string('empresa')->nullable()->after('cargo');
            }
            if (!Schema::hasColumn('invitados_especiales', 'notas')) {
                $table->text('notas')->nullable()->after('estado');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitados_especiales', function (Blueprint $table) {
            $table->dropColumn(['estado', 'email', 'telefono', 'cargo', 'empresa', 'notas']);
        });
    }
};
