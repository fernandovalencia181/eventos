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
        Schema::table('registrations', function (Blueprint $table) {
            // Check if columns exist before adding them to avoid errors if the previous migration actually ran
            if (!Schema::hasColumn('registrations', 'email')) {
                $table->string('email')->after('name');
                $table->string('course')->after('email');
                // Unique per event
                $table->unique(['event_id', 'email']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'email']);
            $table->dropColumn(['email', 'course']);
        });
    }
};
