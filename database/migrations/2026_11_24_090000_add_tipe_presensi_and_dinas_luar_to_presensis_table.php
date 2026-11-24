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
        Schema::table('presensis', function (Blueprint $table) {
            if (!Schema::hasColumn('presensis', 'tipe_presensi')) {
                $table->string('tipe_presensi', 20)->default('kantor')->after('status');
            }
            if (!Schema::hasColumn('presensis', 'foto')) {
                $table->string('foto')->nullable()->after('tipe_presensi');
            }
            if (!Schema::hasColumn('presensis', 'alasan_luar')) {
                $table->text('alasan_luar')->nullable()->after('foto');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('presensis', 'alasan_luar')) {
                $columnsToDrop[] = 'alasan_luar';
            }
            if (Schema::hasColumn('presensis', 'foto')) {
                $columnsToDrop[] = 'foto';
            }
            if (Schema::hasColumn('presensis', 'tipe_presensi')) {
                $columnsToDrop[] = 'tipe_presensi';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
