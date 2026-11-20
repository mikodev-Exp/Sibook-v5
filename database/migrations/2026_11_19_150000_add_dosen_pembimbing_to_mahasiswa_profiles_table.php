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
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->string('nama_dosen_pembimbing')->nullable()->after('program_studi');
            $table->string('no_hp_dosen_pembimbing', 25)->nullable()->after('nama_dosen_pembimbing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->dropColumn(['nama_dosen_pembimbing', 'no_hp_dosen_pembimbing']);
        });
    }
};
