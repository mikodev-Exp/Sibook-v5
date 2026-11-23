<?php

namespace App\Services;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportRekapService
{
    /**
     * Export individual student internship recap (Profile, Stats, Attendance, Logbook)
     */
    public function exportLaporanMahasiswa(User $user, ?string $bulan = null): StreamedResponse
    {
        $user->load(['mahasiswaProfile', 'magang']);

        // Query presensis
        $presensiQuery = $user->presensis()->orderBy('tanggal', 'asc');
        if ($bulan) {
            $presensiQuery->where('tanggal', 'like', "{$bulan}%");
        }
        $presensis = $presensiQuery->get();

        // Query logbooks
        $logbookQuery = $user->logbooks()->orderBy('tanggal', 'asc');
        if ($bulan) {
            $logbookQuery->where('tanggal', 'like', "{$bulan}%");
        }
        $logbooks = $logbookQuery->get();

        // Calculate statistics
        $totalHadir = $user->presensis()->where('status', 'hadir')->count();
        $totalTerlambat = $user->presensis()->where('status', 'terlambat')->count();
        $totalMenitTerlambat = $user->totalMenitTerlambat($bulan);
        $totalIzin = $user->presensis()->where('status', 'izin')->count() 
            + $user->perizinans()->where('status', 'approved')->count();
        $totalSakit = $user->presensis()->where('status', 'sakit')->count();
        $totalAlpha = $user->presensis()->where('status', 'alpha')->count();
        $totalPresensi = $user->presensis()->count();
        $rateKehadiran = $totalPresensi > 0 ? round((($totalHadir + $totalTerlambat) / $totalPresensi) * 100, 1) : 0;
        $totalLogbook = $user->logbooks()->count();

        $spreadsheet = new Spreadsheet();

        // ================= SHEET 1: REKAP KEHADIRAN =================
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Rekap Kehadiran');
        $sheet1->setShowGridLines(true);

        // Header Title
        $sheet1->setCellValue('A1', 'BADAN PUSAT STATISTIK KOTA SURABAYA');
        $sheet1->setCellValue('A2', 'LAPORAN REKAP KEHADIRAN MAHASISWA MAGANG');
        $sheet1->setCellValue('A3', 'Diekspor pada: ' . now()->translatedFormat('d F Y H:i') . ' WIB');

        $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF111827'));
        $sheet1->getStyle('A2')->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE06D28'));
        $sheet1->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF6B7280'));

        // Profile Box
        $profile = $user->mahasiswaProfile;
        $magang = $user->magang;

        $sheet1->setCellValue('A5', 'Nama Mahasiswa');
        $sheet1->setCellValue('B5', ': ' . $user->name);
        $sheet1->setCellValue('D5', 'Universitas');
        $sheet1->setCellValue('E5', ': ' . ($profile->universitas ?? '-'));

        $sheet1->setCellValue('A6', 'NIM');
        $sheet1->setCellValue('B6', ': ' . ($profile->nim ?? '-'));
        $sheet1->setCellValue('D6', 'Program Studi');
        $sheet1->setCellValue('E6', ': ' . ($profile->program_studi ?? ($profile->jurusan ?? '-')));

        $sheet1->setCellValue('A7', 'Email');
        $sheet1->setCellValue('B7', ': ' . $user->email);
        $sheet1->setCellValue('D7', 'Periode Magang');
        $sheet1->setCellValue('E7', ': ' . ($magang ? \Carbon\Carbon::parse($magang->tanggal_mulai)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($magang->tanggal_selesai)->format('d/m/Y') : '-'));

        $sheet1->getStyle('A5:A7')->getFont()->setBold(true);
        $sheet1->getStyle('D5:D7')->getFont()->setBold(true);

        // Statistik Kehadiran Box (Hadir, Izin, Alpha)
        $sheet1->setCellValue('A9', 'STATISTIK KEHADIRAN:');
        $sheet1->getStyle('A9')->getFont()->setBold(true)->setSize(11);

        $statHeaders = ['Hadir', 'Hari Terlambat', 'Tanggungan Terlambat', 'Izin / Sakit', 'Alpha', 'Total Presensi', 'Persentase Kehadiran', 'Total Logbook'];
        $statValues = [
            $totalHadir,
            $totalTerlambat,
            $totalMenitTerlambat . ' menit',
            $totalIzin + $totalSakit,
            $totalAlpha,
            $totalPresensi,
            $rateKehadiran . '%',
            $totalLogbook
        ];

        foreach ($statHeaders as $idx => $label) {
            $col = chr(65 + $idx);
            $sheet1->setCellValue($col . '10', $label);
            $sheet1->setCellValue($col . '11', $statValues[$idx]);
        }

        $sheet1->getStyle('A10:H10')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet1->getStyle('A11:H11')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
        ]);

        // Tabel Detail Presensi
        $sheet1->setCellValue('A13', 'RIWAYAT PRESENSI HARIAN:');
        $sheet1->getStyle('A13')->getFont()->setBold(true)->setSize(11);

        $tableHeaders = ['No', 'Tanggal', 'Jam Masuk', 'Keterlambatan', 'Jam Pulang', 'Tipe Presensi', 'Jarak Masuk (m)', 'Status', 'Keterangan'];
        foreach ($tableHeaders as $idx => $head) {
            $col = chr(65 + $idx);
            $sheet1->setCellValue($col . '14', $head);
        }

        $sheet1->getStyle('A14:I14')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E06D28']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $rowNum = 15;
        foreach ($presensis as $idx => $row) {
            $statusStr = ucfirst($row->status?->value ?? (string) $row->status);
            $tipeStr = ucfirst(str_replace('_', ' ', $row->tipe_presensi ?? 'kantor'));
            $terlambatStr = $row->menit_terlambat > 0 ? ($row->menit_terlambat . ' mnt') : '-';

            $sheet1->setCellValue('A' . $rowNum, $idx + 1);
            $sheet1->setCellValue('B' . $rowNum, \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y'));
            $sheet1->setCellValue('C' . $rowNum, $row->jam_masuk ? substr($row->jam_masuk, 0, 5) : '-');
            $sheet1->setCellValue('D' . $rowNum, $terlambatStr);
            $sheet1->setCellValue('E' . $rowNum, $row->jam_pulang ? substr($row->jam_pulang, 0, 5) : '-');
            $sheet1->setCellValue('F' . $rowNum, $tipeStr);
            $sheet1->setCellValue('G' . $rowNum, $row->jarak_masuk_m ? round($row->jarak_masuk_m) : '-');
            $sheet1->setCellValue('H' . $rowNum, $statusStr);
            $sheet1->setCellValue('I' . $rowNum, $row->keterangan ?? '-');

            $sheet1->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('B' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('C' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('D' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('E' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('F' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet1->getStyle('H' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
        }

        if ($presensis->isEmpty()) {
            $sheet1->setCellValue('A15', 'Belum ada riwayat presensi tercatat.');
            $sheet1->mergeCells('A15:I15');
            $sheet1->getStyle('A15')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowNum = 16;
        }

        $sheet1->getStyle('A14:I' . ($rowNum - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
        ]);

        foreach (range('A', 'I') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        // ================= SHEET 2: REKAP LOGBOOK =================
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Rekap Logbook');
        $sheet2->setShowGridLines(true);

        $sheet2->setCellValue('A1', 'BADAN PUSAT STATISTIK KOTA SURABAYA');
        $sheet2->setCellValue('A2', 'LAPORAN REKAP LOGBOOK MAHASISWA MAGANG: ' . strtoupper($user->name));
        $sheet2->setCellValue('A3', 'Total Catatan Logbook: ' . $logbooks->count());

        $sheet2->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet2->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE06D28'));
        $sheet2->getStyle('A3')->getFont()->setItalic(true)->setSize(9);

        $logbookHeaders = ['No', 'Tanggal', 'Divisi / Tim', 'Judul Kegiatan', 'Deskripsi Kegiatan', 'Jam Kerja', 'Status Verifikasi', 'Catatan Verifikator', 'Tautan Bukti'];
        foreach ($logbookHeaders as $idx => $head) {
            $col = chr(65 + $idx);
            $sheet2->setCellValue($col . '5', $head);
        }

        $sheet2->getStyle('A5:I5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $rowNum2 = 6;
        foreach ($logbooks as $idx => $lb) {
            $sheet2->setCellValue('A' . $rowNum2, $idx + 1);
            $sheet2->setCellValue('B' . $rowNum2, \Carbon\Carbon::parse($lb->tanggal)->translatedFormat('d F Y'));
            $sheet2->setCellValue('C' . $rowNum2, $lb->tim ?? 'Kegiatan Lainnya');
            $sheet2->setCellValue('D' . $rowNum2, $lb->kegiatan ?? '-');
            $sheet2->setCellValue('E' . $rowNum2, $lb->deskripsi ?? '-');
            $sheet2->setCellValue('F' . $rowNum2, ($lb->jam_mulai ? substr($lb->jam_mulai, 0, 5) : '-') . ' - ' . ($lb->jam_selesai ? substr($lb->jam_selesai, 0, 5) : '-'));
            $sheet2->setCellValue('G' . $rowNum2, ucfirst($lb->status?->value ?? (string) $lb->status));
            $sheet2->setCellValue('H' . $rowNum2, $lb->catatan_verifikator ?? '-');
            $sheet2->setCellValue('I' . $rowNum2, $lb->link_bukti ?? '-');

            $sheet2->getStyle('A' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('B' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('C' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('F' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle('G' . $rowNum2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum2++;
        }

        if ($logbooks->isEmpty()) {
            $sheet2->setCellValue('A6', 'Belum ada data logbook kegiatan.');
            $sheet2->mergeCells('A6:I6');
            $sheet2->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowNum2 = 7;
        }

        $sheet2->getStyle('A5:I' . ($rowNum2 - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
        ]);

        foreach (range('A', 'I') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        // Output stream download
        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $user->name);
        $filename = "Laporan_Magang_{$cleanName}_" . date('Ymd_His') . ".xlsx";

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export all active internship students with their attendance recap
     */
    public function exportSemuaMahasiswa($mahasiswas): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Mahasiswa Magang');
        $sheet->setShowGridLines(true);

        // Header Title
        $sheet->setCellValue('A1', 'BADAN PUSAT STATISTIK KOTA SURABAYA');
        $sheet->setCellValue('A2', 'REKAPITULASI MAHASISWA MAGANG & STATISTIK KEHADIRAN');
        $sheet->setCellValue('A3', 'Diekspor pada: ' . now()->translatedFormat('d F Y H:i') . ' WIB');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE06D28'));

        $headers = [
            'No', 'Nama Mahasiswa', 'NIM', 'Universitas', 'Program Studi', 'Email', 'No HP', 
            'Periode Mulai', 'Periode Selesai', 'Hadir', 'Hari Terlambat', 'Tanggungan Terlambat (Mnt)', 'Izin/Sakit', 'Alpha', 
            'Total Presensi', 'Persentase Kehadiran', 'Total Logbook'
        ];

        foreach ($headers as $idx => $title) {
            $col = chr(65 + ($idx % 26));
            if ($idx >= 26) {
                $col = chr(64 + intdiv($idx, 26)) . chr(65 + ($idx % 26));
            }
            $sheet->setCellValue($col . '5', $title);
        }

        $lastCol = chr(64 + intdiv(count($headers) - 1, 26)) . chr(65 + ((count($headers) - 1) % 26));
        if (count($headers) <= 26) {
            $lastCol = chr(64 + count($headers));
        }

        $sheet->getStyle('A5:' . $lastCol . '5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $rowNum = 6;
        foreach ($mahasiswas as $idx => $mhs) {
            $profile = $mhs->mahasiswaProfile;
            $magang = $mhs->magang;

            $hadir = $mhs->presensis()->where('status', 'hadir')->count();
            $terlambat = $mhs->presensis()->where('status', 'terlambat')->count();
            $totalMenit = $mhs->totalMenitTerlambat();
            $izin = $mhs->presensis()->where('status', 'izin')->count() + $mhs->perizinans()->where('status', 'approved')->count();
            $alpha = $mhs->presensis()->where('status', 'alpha')->count();
            $total = $mhs->presensis()->count();
            $rate = $total > 0 ? round((($hadir + $terlambat) / $total) * 100, 1) : 0;
            $logbook = $mhs->logbooks()->count();

            $sheet->setCellValue('A' . $rowNum, $idx + 1);
            $sheet->setCellValue('B' . $rowNum, $mhs->name);
            $sheet->setCellValue('C' . $rowNum, $profile->nim ?? '-');
            $sheet->setCellValue('D' . $rowNum, $profile->universitas ?? '-');
            $sheet->setCellValue('E' . $rowNum, $profile->program_studi ?? ($profile->jurusan ?? '-'));
            $sheet->setCellValue('F' . $rowNum, $mhs->email);
            $sheet->setCellValue('G' . $rowNum, $profile->no_hp ?? '-');
            $sheet->setCellValue('H' . $rowNum, $magang ? \Carbon\Carbon::parse($magang->tanggal_mulai)->format('d/m/Y') : '-');
            $sheet->setCellValue('I' . $rowNum, $magang ? \Carbon\Carbon::parse($magang->tanggal_selesai)->format('d/m/Y') : '-');
            $sheet->setCellValue('J' . $rowNum, $hadir);
            $sheet->setCellValue('K' . $rowNum, $terlambat);
            $sheet->setCellValue('L' . $rowNum, $totalMenit);
            $sheet->setCellValue('M' . $rowNum, $izin);
            $sheet->setCellValue('N' . $rowNum, $alpha);
            $sheet->setCellValue('O' . $rowNum, $total);
            $sheet->setCellValue('P' . $rowNum, $rate . '%');
            $sheet->setCellValue('Q' . $rowNum, $logbook);

            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $rowNum . ':Q' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
        }

        $sheet->getStyle('A5:Q' . ($rowNum - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
        ]);

        foreach (range('A', 'Q') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Rekap_Mahasiswa_Magang_" . date('Ymd_His') . ".xlsx";
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export all presensi records to Excel
     */
    public function exportPresensi($presensis): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Presensi');
        $sheet->setShowGridLines(true);

        $sheet->setCellValue('A1', 'BADAN PUSAT STATISTIK KOTA SURABAYA');
        $sheet->setCellValue('A2', 'LAPORAN REKAPITULASI PRESENSI MAHASISWA MAGANG');
        $sheet->setCellValue('A3', 'Diekspor pada: ' . now()->translatedFormat('d F Y H:i') . ' WIB');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE06D28'));

        $headers = ['No', 'Nama Mahasiswa', 'NIM', 'Universitas', 'Tanggal', 'Jam Masuk', 'Keterlambatan (Mnt)', 'Jam Pulang', 'Tipe Presensi', 'Jarak Masuk (m)', 'Status', 'Keterangan'];
        foreach ($headers as $idx => $title) {
            $col = chr(65 + $idx);
            $sheet->setCellValue($col . '5', $title);
        }

        $sheet->getStyle('A5:L5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $rowNum = 6;
        foreach ($presensis as $idx => $p) {
            $sheet->setCellValue('A' . $rowNum, $idx + 1);
            $sheet->setCellValue('B' . $rowNum, $p->user->name ?? '-');
            $sheet->setCellValue('C' . $rowNum, $p->user->mahasiswaProfile->nim ?? '-');
            $sheet->setCellValue('D' . $rowNum, $p->user->mahasiswaProfile->universitas ?? '-');
            $sheet->setCellValue('E' . $rowNum, \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y'));
            $sheet->setCellValue('F' . $rowNum, $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-');
            $sheet->setCellValue('G' . $rowNum, $p->menit_terlambat > 0 ? ($p->menit_terlambat . ' mnt') : '-');
            $sheet->setCellValue('H' . $rowNum, $p->jam_pulang ? substr($p->jam_pulang, 0, 5) : '-');
            $sheet->setCellValue('I' . $rowNum, ucfirst(str_replace('_', ' ', $p->tipe_presensi ?? 'kantor')));
            $sheet->setCellValue('J' . $rowNum, $p->jarak_masuk_m ? round($p->jarak_masuk_m) : '-');
            $sheet->setCellValue('K' . $rowNum, ucfirst($p->status?->value ?? (string) $p->status));
            $sheet->setCellValue('L' . $rowNum, $p->keterangan ?? '-');

            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $rowNum . ':K' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
        }

        if ($presensis->isEmpty()) {
            $sheet->setCellValue('A6', 'Belum ada data presensi.');
            $sheet->mergeCells('A6:L6');
            $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowNum = 7;
        }

        $sheet->getStyle('A5:L' . ($rowNum - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
        ]);

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Rekap_Presensi_" . date('Ymd_His') . ".xlsx";
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export all logbook records to Excel
     */
    public function exportLogbook($logbooks): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Logbook');
        $sheet->setShowGridLines(true);

        $sheet->setCellValue('A1', 'BADAN PUSAT STATISTIK KOTA SURABAYA');
        $sheet->setCellValue('A2', 'LAPORAN REKAPITULASI LOGBOOK MAHASISWA MAGANG');
        $sheet->setCellValue('A3', 'Diekspor pada: ' . now()->translatedFormat('d F Y H:i') . ' WIB');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE06D28'));

        $headers = ['No', 'Nama Mahasiswa', 'NIM', 'Universitas', 'Tanggal', 'Divisi/Tim', 'Kegiatan', 'Deskripsi', 'Jam Kerja', 'Status Verifikasi', 'Catatan Verifikator'];
        foreach ($headers as $idx => $title) {
            $col = chr(65 + $idx);
            $sheet->setCellValue($col . '5', $title);
        }

        $sheet->getStyle('A5:K5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $rowNum = 6;
        foreach ($logbooks as $idx => $lb) {
            $sheet->setCellValue('A' . $rowNum, $idx + 1);
            $sheet->setCellValue('B' . $rowNum, $lb->user->name ?? '-');
            $sheet->setCellValue('C' . $rowNum, $lb->user->mahasiswaProfile->nim ?? '-');
            $sheet->setCellValue('D' . $rowNum, $lb->user->mahasiswaProfile->universitas ?? '-');
            $sheet->setCellValue('E' . $rowNum, \Carbon\Carbon::parse($lb->tanggal)->translatedFormat('d F Y'));
            $sheet->setCellValue('F' . $rowNum, $lb->tim ?? 'Kegiatan Lainnya');
            $sheet->setCellValue('G' . $rowNum, $lb->kegiatan ?? '-');
            $sheet->setCellValue('H' . $rowNum, $lb->deskripsi ?? '-');
            $sheet->setCellValue('I' . $rowNum, ($lb->jam_mulai ? substr($lb->jam_mulai, 0, 5) : '-') . ' - ' . ($lb->jam_selesai ? substr($lb->jam_selesai, 0, 5) : '-'));
            $sheet->setCellValue('J' . $rowNum, ucfirst($lb->status?->value ?? (string) $lb->status));
            $sheet->setCellValue('K' . $rowNum, $lb->catatan_verifikator ?? '-');

            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $rowNum . ':F' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $rowNum . ':J' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
        }

        if ($logbooks->isEmpty()) {
            $sheet->setCellValue('A6', 'Belum ada data logbook.');
            $sheet->mergeCells('A6:K6');
            $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowNum = 7;
        }

        $sheet->getStyle('A5:K' . ($rowNum - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
        ]);

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Rekap_Logbook_" . date('Ymd_His') . ".xlsx";
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
