<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\KelompokKuota;
use App\Models\PengaturanMagang;
use Illuminate\Database\Seeder;

class PersyaratanMagangSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kelompok STEM (Sains, Teknologi, Teknik & Matematika)
        $stemGroup = KelompokKuota::updateOrCreate(
            ['nama' => 'Sains, Teknologi, Teknik & Matematika'],
            [
                'kuota'        => 35,
                'aktif'        => 18,
                'is_catch_all' => false,
                'urutan'       => 1,
            ]
        );

        $stemMajors = [
            'Sains/IPA',
            'Aktuaria',
            'Sistem Informasi',
            'Ilmu Komputer',
            'Teknik Informatika',
            'Manajemen Informatika',
            'Matematika',
            'Statistik',
        ];

        foreach ($stemMajors as $majorName) {
            Jurusan::updateOrCreate(
                ['nama' => $majorName],
                ['kelompok_kuota_id' => $stemGroup->id]
            );
        }

        // 2. Kelompok "Lainnya" (is_catch_all)
        KelompokKuota::updateOrCreate(
            ['is_catch_all' => true],
            [
                'nama'        => 'Lainnya',
                'kuota'       => 10,
                'aktif'       => 8,
                'urutan'      => 99,
            ]
        );

        // 3. Pengaturan Magang (Persyaratan, Catatan, Status, Link SPS)
        PengaturanMagang::updateOrCreate(
            ['id' => 1],
            [
                'status'              => 'soon',
                'catatan_status'      => 'Pembukaan magang dibuka lagi pada akhir Agustus',
                'tanggal_buka'        => null,
                'persyaratan'         => [
                    'Mahasiswa aktif minimal semester 5 atau setara',
                    'Memiliki Surat Pengantar Resmi / Izin Magang dari Universitas / Fakultas',
                    'Transkrip nilai akademik terbaru dengan IPK minimal 3.00',
                    'Curriculum Vitae (CV) terbaru yang memuat pengalaman & keahlian',
                    'Bersedia mengikuti masa magang secara luring (WFO) penuh waktu sesuai jam operasional kantor BPS',
                    'Memiliki laptop dan perangkat pendukung kerja pribadi',
                ],
                'catatan_persyaratan' => 'Pendaftaran dilakukan secara daring melalui sistem SIBOOK. Pastikan seluruh berkas pendukung telah dipindai dalam format PDF yang jelas dan terbaca.',
                'tautan'              => [
                    [
                        'label' => 'Portal Layanan Terpadu SPS BPS',
                        'url'   => 'https://sps.bps.go.id',
                    ],
                    [
                        'label' => 'Website Resmi BPS Kota Surabaya',
                        'url'   => 'https://surabayakota.bps.go.id',
                    ],
                ],
            ]
        );
    }
}
