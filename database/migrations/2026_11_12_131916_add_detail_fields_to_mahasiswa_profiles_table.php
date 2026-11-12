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
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('link_pasfoto')->nullable();
            $table->string('username_ig')->nullable();
            $table->boolean('status_konversi')->default(false);
            $table->string('device')->nullable();
            $table->boolean('punya_bpjs')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'link_pasfoto', 'username_ig', 'status_konversi', 'device', 'punya_bpjs']);
        });
    }
};
