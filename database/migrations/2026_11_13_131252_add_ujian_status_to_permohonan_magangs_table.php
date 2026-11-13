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
        Schema::table('permohonan_magangs', function (Blueprint $table) {
            $table->string('status_ujian_esai', 20)->default('pending'); // pending, lulus, gagal
            $table->string('status_ujian_tulis', 20)->default('pending');
            $table->string('status_interview', 20)->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_magangs', function (Blueprint $table) {
            $table->dropColumn(['status_ujian_esai', 'status_ujian_tulis', 'status_interview']);
        });
    }
};
