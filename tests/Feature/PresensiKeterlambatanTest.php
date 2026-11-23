<?php

namespace Tests\Feature;

use App\Models\Presensi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresensiKeterlambatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_menit_keterlambatan_dihitung_tepat_dari_jam_0730(): void
    {
        $presensiTepat = new Presensi(['jam_masuk' => '07:28:00']);
        $this->assertEquals(0, $presensiTepat->menit_terlambat);

        $presensiPas = new Presensi(['jam_masuk' => '07:30:00']);
        $this->assertEquals(0, $presensiPas->menit_terlambat);

        // Toleransi sampai 07:30:30 masih tepat waktu (0 menit)
        $presensiToleransiDetik = new Presensi(['jam_masuk' => '07:30:30']);
        $this->assertEquals(0, $presensiToleransiDetik->menit_terlambat);

        // Lewat dari 07:30:30 (misal 07:30:45) -> terlambat 1 menit
        $presensiLewatToleransi = new Presensi(['jam_masuk' => '07:30:45']);
        $this->assertEquals(1, $presensiLewatToleransi->menit_terlambat);

        // Contoh spesifik dari user: 07:32 -> terlambat 2 menit
        $presensiDuaMenit = new Presensi(['jam_masuk' => '07:32:00']);
        $this->assertEquals(2, $presensiDuaMenit->menit_terlambat);
        $this->assertEquals('2 menit', $presensiDuaMenit->formatted_terlambat);

        // Keterlambatan 15 menit
        $presensiLimaBelas = new Presensi(['jam_masuk' => '07:45:00']);
        $this->assertEquals(15, $presensiLimaBelas->menit_terlambat);
        $this->assertEquals('15 menit', $presensiLimaBelas->formatted_terlambat);

        // Keterlambatan lebih dari 1 jam (misal 08:35 -> 65 menit)
        $presensiSatuJam = new Presensi(['jam_masuk' => '08:35:00']);
        $this->assertEquals(65, $presensiSatuJam->menit_terlambat);
        $this->assertEquals('1 jam 5 menit', $presensiSatuJam->formatted_terlambat);
    }

    public function test_akumulasi_keterlambatan_mahasiswa_direkap_seluruh_periode(): void
    {
        $user = User::factory()->create();

        // Hari 1: 07:32 -> 2 menit terlambat
        Presensi::create([
            'user_id' => $user->id,
            'tanggal' => '2026-03-02',
            'jam_masuk' => '07:32:00',
            'status' => 'terlambat',
        ]);

        // Hari 2: 07:25 -> 0 menit (tepat waktu)
        Presensi::create([
            'user_id' => $user->id,
            'tanggal' => '2026-03-03',
            'jam_masuk' => '07:25:00',
            'status' => 'hadir',
        ]);

        // Hari 3: 07:38 -> 8 menit terlambat
        Presensi::create([
            'user_id' => $user->id,
            'tanggal' => '2026-03-04',
            'jam_masuk' => '07:38:00',
            'status' => 'terlambat',
        ]);

        // Total akumulasi keterlambatan = 2 + 0 + 8 = 10 menit
        $this->assertEquals(10, $user->totalMenitTerlambat());
        $this->assertEquals('10 menit', $user->formattedTotalTerlambat());
    }
}
