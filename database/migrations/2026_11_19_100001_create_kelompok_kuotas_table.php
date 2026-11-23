<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok_kuotas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedInteger('kuota')->default(0);
            $table->unsignedInteger('aktif')->default(0);
            $table->boolean('is_catch_all')->default(false);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompok_kuotas');
    }
};
