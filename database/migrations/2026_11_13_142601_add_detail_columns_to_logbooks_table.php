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
            $table->string('tim', 50)->nullable();
            $table->string('jenis_gsbpm', 50)->nullable();
            $table->time('jam_mulai')->nullable();
            $table->string('link_bukti')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn(['tim', 'jenis_gsbpm', 'jam_mulai', 'link_bukti']);
        });
    }
};
