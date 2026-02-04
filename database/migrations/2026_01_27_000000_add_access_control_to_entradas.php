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
        Schema::table('entradas', function (Blueprint $table) {
            $table->string('token_reentrada')->nullable()->after('token_seguridad_qr');
            $table->timestamp('token_reentrada_expira')->nullable()->after('token_reentrada');
            $table->string('token_invitado')->unique()->nullable()->after('token_reentrada_expira');
            $table->string('dispositivo_invitado')->nullable()->after('token_invitado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entradas', function (Blueprint $table) {
            $table->dropColumn([
                'token_reentrada',
                'token_reentrada_expira',
                'token_invitado',
                'dispositivo_invitado'
            ]);
        });
    }
};
