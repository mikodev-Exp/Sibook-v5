<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        // Koordinat & radius kantor BPS Kota Surabaya
        // Silakan super user update via panel pengaturan bila perlu
        Pengaturan::set('kantor.nama',      'BPS Kota Surabaya', 'string', 'kantor');
        Pengaturan::set('kantor.lat',       '-7.2863000',        'float',  'kantor');
        Pengaturan::set('kantor.lng',       '112.7358000',       'float',  'kantor');
        Pengaturan::set('kantor.radius_m',  '1000',              'int',    'kantor');

        // Jam kerja default
        Pengaturan::set('presensi.jam_masuk_mulai',  '07:00',        'string', 'presensi');
        Pengaturan::set('presensi.jam_masuk_batas',  '08:15',        'string', 'presensi');
        Pengaturan::set('presensi.jam_pulang_min',   '16:00',        'string', 'presensi');
        Pengaturan::set('presensi.timezone',         'Asia/Jakarta', 'string', 'presensi');
    }
}