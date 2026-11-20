<?php

namespace App\Services;

use App\Models\Jurusan;
use App\Models\KelompokKuota;
use App\Models\PengaturanMagang;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PersyaratanMagangService
{
    public const CACHE_KEY = 'landing_persyaratan_magang';

    /**
     * Ambil data teragregasi untuk landing page (di-cache).
     */
    public function getLandingData(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $setting = PengaturanMagang::current();
            $groups = KelompokKuota::with('jurusans')->orderBy('urutan')->orderBy('nama')->get();
            $unassignedMajors = Jurusan::whereNull('kelompok_kuota_id')->orderBy('nama')->get();

            // Sesuai requirement: Jurusan yang belum dikelompokkan otomatis tampil di kelompok "Lainnya"
            $catchAllGroup = $groups->firstWhere('is_catch_all', true);

            if ($catchAllGroup && $unassignedMajors->isNotEmpty()) {
                $mergedMajors = $catchAllGroup->jurusans->merge($unassignedMajors)->unique('id')->sortBy('nama')->values();
                $catchAllGroup->setRelation('jurusans', $mergedMajors);
            }

            // Hitung total kuota dan slot
            $totalQuota = (int) $groups->sum('kuota');
            $totalActive = (int) $groups->sum('aktif');
            $totalRemaining = max(0, $totalQuota - $totalActive);
            $totalPercentage = $totalQuota > 0 ? (int) min(100, round(($totalActive / $totalQuota) * 100)) : 0;

            // Tanggal diperbarui terbaru dari setting, groups, atau majors
            $dates = collect([
                $setting->updated_at,
                $groups->max('updated_at'),
                Jurusan::max('updated_at'),
            ])->filter()->map(fn ($d) => Carbon::parse($d));

            $lastUpdated = $dates->isNotEmpty() ? $dates->max()->toIso8601String() : Carbon::now()->toIso8601String();

            // Format data groups ke bentuk array murni (aman dari serialisasi Eloquent di cache)
            $formattedGroups = $groups->map(function ($g) {
                $majorList = $g->jurusans->map(fn ($m) => [
                    'id'   => (int) $m->id,
                    'nama' => (string) $m->nama,
                    'name' => (string) $m->nama,
                ])->values()->all();

                return [
                    'id'                   => (int) $g->id,
                    'nama'                 => (string) $g->nama,
                    'name'                 => (string) $g->nama,
                    'kuota'                => (int) $g->kuota,
                    'quota'                => (int) $g->kuota,
                    'aktif'                => (int) $g->aktif,
                    'active'               => (int) $g->aktif,
                    'sisa'                 => (int) $g->sisa,
                    'remaining'            => (int) $g->sisa,
                    'persentase'           => (int) $g->persentase,
                    'percentage'           => (int) $g->persentase,
                    'persentase_sisa'      => (float) $g->persentase_sisa,
                    'remaining_percentage' => (float) $g->persentase_sisa,
                    'is_full'              => (bool) $g->is_full,
                    'status_color'         => (string) $g->status_color,
                    'is_catch_all'         => (bool) $g->is_catch_all,
                    'urutan'               => (int) $g->urutan,
                    'sort_order'           => (int) $g->urutan,
                    'jurusans'             => $majorList,
                    'majors'               => $majorList,
                ];
            })->values()->all();

            $requirements = $setting->persyaratan ?? [];
            $requirementsNote = $setting->catatan_persyaratan;
            $status = $setting->status;
            $statusLabel = $setting->status_label;
            $statusNote = $setting->catatan_status;

            return [
                'groups'              => $formattedGroups,
                'kelompok'            => $formattedGroups,
                'total_quota'         => $totalQuota,
                'total_kuota'         => $totalQuota,
                'total_active'        => $totalActive,
                'total_aktif'         => $totalActive,
                'total_remaining'     => $totalRemaining,
                'total_sisa'          => $totalRemaining,
                'total_percentage'    => $totalPercentage,
                'total_persentase'    => $totalPercentage,
                'last_updated'        => $lastUpdated,
                'terakhir_diperbarui' => $lastUpdated,
                'status'              => $status,
                'status_label'        => $statusLabel,
                'label_status'        => $statusLabel,
                'status_note'         => $statusNote,
                'catatan_status'      => $statusNote,
                'requirements'        => $requirements,
                'persyaratan'         => $requirements,
                'requirements_note'   => $requirementsNote,
                'catatan_persyaratan' => $requirementsNote,
            ];
        });
    }

    /**
     * Ambil data langsung dari database untuk panel admin (tanpa cache).
     */
    public function getAdminData(): array
    {
        $setting = PengaturanMagang::current();
        $groups = KelompokKuota::with('jurusans')->orderBy('urutan')->orderBy('nama')->get();
        $unassignedMajors = Jurusan::whereNull('kelompok_kuota_id')->orderBy('nama')->get();
        $allMajors = Jurusan::orderBy('nama')->get();

        $totalQuota = (int) $groups->sum('kuota');
        $totalActive = (int) $groups->sum('aktif');
        $totalRemaining = max(0, $totalQuota - $totalActive);

        $dates = collect([
            $setting->updated_at,
            $groups->max('updated_at'),
            Jurusan::max('updated_at'),
        ])->filter()->map(fn ($d) => Carbon::parse($d));

        $lastUpdated = $dates->isNotEmpty() ? $dates->max() : Carbon::now();

        return [
            'setting'          => $setting,
            'groups'           => $groups,
            'unassignedMajors' => $unassignedMajors,
            'allMajors'        => $allMajors,
            'totalQuota'       => $totalQuota,
            'totalActive'      => $totalActive,
            'totalRemaining'   => $totalRemaining,
            'lastUpdated'      => $lastUpdated,
        ];
    }

    /**
     * Hapus cache landing page setiap kali admin menyimpan perubahan.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        // Hapus juga legacy cache key bila ada
        Cache::forget('landing_internship_requirements');
    }
}
