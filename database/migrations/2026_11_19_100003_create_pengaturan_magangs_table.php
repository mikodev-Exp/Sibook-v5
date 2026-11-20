<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_magangs', function (Blueprint $table) {
            $table->id();
            $table->json('persyaratan')->nullable();
            $table->text('catatan_persyaratan')->nullable();
            $table->string('status', 20)->default('soon'); // open, soon, closed
            $table->string('catatan_status')->nullable();
            $table->date('tanggal_buka')->nullable();
            $table->json('tautan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_magangs');
    }
};
