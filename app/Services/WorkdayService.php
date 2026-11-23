<?php

namespace App\Services;

use Carbon\Carbon;

/**
 * WorkdayService
 *
 * Menghitung jumlah hari kerja efektif antara dua tanggal:
 * - Tidak termasuk Sabtu & Minggu
 * - Tidak termasuk hari libur nasional Indonesia
 *
 * Aturan periode magang:
 * - Minimal 3 bulan kerja efektif (≥ MIN_WORKDAYS hari kerja) tanpa memandang status konversi.
 */
class WorkdayService
{
    /**
     * Minimal hari kerja efektif untuk periode magang (± 3 bulan × 22 hari kerja/bulan).
     * BPS menetapkan 65 hari kerja sebagai setara 3 bulan efektif.
     */
    public const MIN_WORKDAYS = 65;

    /**
     * Daftar hari libur nasional Indonesia (format: 'Y-m-d').
     * Perbarui setiap tahun atau ambil dari API kalender.
     */
    protected static function getHolidays(): array
    {
        return [
            // --- 2025 ---
            '2025-01-01', // Tahun Baru Masehi
            '2025-01-27', // Isra Miraj
            '2025-01-28', // Isra Miraj (cuti bersama)
            '2025-01-29', // Hari Raya Imlek
            '2025-03-29', // Cuti Bersama Nyepi
            '2025-03-31', // Hari Raya Nyepi
            '2025-04-18', // Wafat Isa Al Masih
            '2025-04-19', // Cuti Bersama
            '2025-04-20', // Kebangkitan Isa Al Masih
            '2025-05-01', // Hari Buruh
            '2025-05-12', // Hari Raya Waisak
            '2025-05-29', // Kenaikan Isa Al Masih
            '2025-06-01', // Hari Lahir Pancasila
            '2025-03-30', // Hari Raya Idul Fitri 1446 H (cuti bersama)
            '2025-03-31', // Hari Raya Idul Fitri 1446 H
            '2025-04-01', // Hari Raya Idul Fitri 1446 H
            '2025-04-02', // Hari Raya Idul Fitri 1446 H (cuti bersama)
            '2025-06-06', // Hari Raya Idul Adha 1446 H
            '2025-06-27', // Tahun Baru Islam 1447 H
            '2025-08-17', // HUT Kemerdekaan RI
            '2025-09-05', // Maulid Nabi Muhammad SAW
            '2025-12-25', // Hari Raya Natal
            '2025-12-26', // Cuti Bersama Natal

            // --- 2026 ---
            '2026-01-01', // Tahun Baru Masehi
            '2026-01-16', // Isra Miraj
            '2026-01-17', // Cuti Bersama Isra Miraj
            '2026-02-17', // Tahun Baru Imlek
            '2026-03-20', // Hari Raya Nyepi
            '2026-03-19', // Hari Raya Idul Fitri 1447 H (cuti bersama)
            '2026-03-20', // Hari Raya Idul Fitri 1447 H
            '2026-03-21', // Hari Raya Idul Fitri 1447 H
            '2026-03-22', // Cuti Bersama
            '2026-03-23', // Cuti Bersama
            '2026-04-02', // Kamis Putih / Wafat Isa Al Masih
            '2026-04-05', // Kebangkitan Isa Al Masih
            '2026-05-01', // Hari Buruh
            '2026-05-02', // Cuti Bersama Waisak
            '2026-05-03', // Waisak
            '2026-05-14', // Kenaikan Isa Al Masih
            '2026-05-27', // Hari Raya Idul Adha 1447 H
            '2026-06-01', // Hari Lahir Pancasila
            '2026-06-17', // Tahun Baru Islam 1448 H
            '2026-08-17', // HUT Kemerdekaan RI
            '2026-08-26', // Maulid Nabi Muhammad SAW
            '2026-12-25', // Natal
            '2026-12-26', // Cuti Bersama Natal
            '2026-12-31', // Cuti Bersama Tahun Baru

            // --- 2027 ---
            '2027-01-01', // Tahun Baru Masehi
            '2027-01-05', // Isra Miraj
            '2027-02-06', // Tahun Baru Imlek
            '2027-03-09', // Hari Raya Idul Fitri 1448 H (cuti bersama)
            '2027-03-10', // Hari Raya Idul Fitri 1448 H
            '2027-03-11', // Hari Raya Idul Fitri 1448 H
            '2027-03-26', // Hari Raya Nyepi
            '2027-03-26', // Wafat Isa Al Masih
            '2027-04-25', // Kebangkitan Isa Al Masih / Waisak
            '2027-05-01', // Hari Buruh
            '2027-05-03', // Kenaikan Isa Al Masih
            '2027-05-17', // Hari Raya Idul Adha 1448 H
            '2027-06-01', // Hari Lahir Pancasila
            '2027-06-07', // Tahun Baru Islam 1449 H
            '2027-08-16', // Cuti Bersama HUT RI
            '2027-08-17', // HUT Kemerdekaan RI
            '2027-08-16', // Maulid Nabi Muhammad SAW
            '2027-12-25', // Natal
        ];
    }

    /**
     * Hitung jumlah hari kerja efektif antara $start dan $end (inklusif kedua ujung).
     * Tidak termasuk Sabtu, Minggu, dan hari libur nasional.
     */
    public static function hitungHariKerja(Carbon $start, Carbon $end): int
    {
        if ($end->lt($start)) {
            return 0;
        }

        $holidays = array_flip(self::getHolidays());
        $count    = 0;
        $current  = $start->copy()->startOfDay();
        $endDay   = $end->copy()->startOfDay();

        while ($current->lte($endDay)) {
            $dow = $current->dayOfWeek; // 0=Minggu, 6=Sabtu
            if ($dow !== Carbon::SATURDAY && $dow !== Carbon::SUNDAY) {
                if (!isset($holidays[$current->format('Y-m-d')])) {
                    $count++;
                }
            }
            $current->addDay();
        }

        return $count;
    }

    /**
     * Hitung tanggal selesai paling awal berdasarkan tanggal mulai
     * dengan target minimal MIN_WORKDAYS hari kerja efektif.
     */
    public static function tanggalSelesaiMinimal(Carbon $start): Carbon
    {
        $holidays = array_flip(self::getHolidays());
        $count    = 0;
        $current  = $start->copy()->startOfDay();

        while ($count < self::MIN_WORKDAYS) {
            $dow = $current->dayOfWeek;
            if ($dow !== Carbon::SATURDAY && $dow !== Carbon::SUNDAY) {
                if (!isset($holidays[$current->format('Y-m-d')])) {
                    $count++;
                }
            }
            if ($count < self::MIN_WORKDAYS) {
                $current->addDay();
            }
        }

        return $current;
    }
}
