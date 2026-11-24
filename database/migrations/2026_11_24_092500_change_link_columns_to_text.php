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
        Schema::table('logbooks', function (Blueprint $table) {
            $table->text('link_bukti')->nullable()->change();
        });

        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->text('link_pasfoto')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->string('link_bukti', 255)->nullable()->change();
        });

        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->string('link_pasfoto', 255)->nullable()->change();
        });
    }
};
