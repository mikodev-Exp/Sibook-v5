<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJurusanRequest;
use App\Http\Requests\Admin\StoreKelompokKuotaRequest;
use App\Http\Requests\Admin\UpdateLinkSpsRequest;
use App\Http\Requests\Admin\UpdatePengaturanMagangRequest;
use App\Models\Jurusan;
use App\Models\KelompokKuota;
use App\Models\PengaturanMagang;
use App\Services\PersyaratanMagangService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersyaratanMagangController extends Controller
{
    /**
     * Tampilan utama manajemen persyaratan, kuota, dan link SPS.
     */
    public function index(PersyaratanMagangService $service): View
    {
        $data = $service->getAdminData();

        return view('admin.persyaratan-magang.index', $data);
    }

    /**
     * Perbarui poin persyaratan, catatan, dan status pembukaan magang.
     */
    public function updatePengaturan(
        UpdatePengaturanMagangRequest $request,
        PersyaratanMagangService $service
    ): RedirectResponse {
        $setting = PengaturanMagang::current();

        $inputPersyaratan = $request->input('persyaratan', $request->input('requirements', []));
        $persyaratan = array_values(array_filter(
            array_map('trim', is_array($inputPersyaratan) ? $inputPersyaratan : []),
            fn ($item) => ! empty($item)
        ));

        $setting->update([
            'status'              => $request->input('status'),
            'catatan_status'      => $request->input('catatan_status', $request->input('status_note')),
            'tanggal_buka'        => $request->input('tanggal_buka', $request->input('opens_at')),
            'persyaratan'         => $persyaratan,
            'catatan_persyaratan' => $request->input('catatan_persyaratan', $request->input('requirements_note')),
        ]);

        $service->clearCache();

        return back()->with('success', 'Persyaratan dan status magang berhasil diperbarui.');
    }

    public function updateSettings(UpdatePengaturanMagangRequest $request, PersyaratanMagangService $service): RedirectResponse
    {
        return $this->updatePengaturan($request, $service);
    }

    /**
     * Perbarui daftar tautan SPS (hanya tampil di panel admin).
     */
    public function updateTautan(
        UpdateLinkSpsRequest $request,
        PersyaratanMagangService $service
    ): RedirectResponse {
        $setting = PengaturanMagang::current();

        $inputTautan = $request->input('tautan', $request->input('links', []));
        $tautan = array_values(array_filter(
            is_array($inputTautan) ? $inputTautan : [],
            fn ($link) => ! empty($link['label'] ?? null) && ! empty($link['url'] ?? null)
        ));

        $setting->update(['tautan' => $tautan]);

        $service->clearCache();

        return back()->with('success', 'Daftar tautan SPS berhasil diperbarui.');
    }

    public function updateLinks(UpdateLinkSpsRequest $request, PersyaratanMagangService $service): RedirectResponse
    {
        return $this->updateTautan($request, $service);
    }

    /**
     * Tambah kelompok kuota baru.
     */
    public function storeKelompok(
        StoreKelompokKuotaRequest $request,
        PersyaratanMagangService $service
    ): RedirectResponse {
        $group = KelompokKuota::create([
            'nama'         => $request->input('nama', $request->input('name')),
            'kuota'        => (int) $request->input('kuota', $request->input('quota')),
            'aktif'        => (int) $request->input('aktif', $request->input('active')),
            'is_catch_all' => (bool) $request->input('is_catch_all', false),
            'urutan'       => (int) $request->input('urutan', $request->input('sort_order', 0)),
        ]);

        $jurusanIds = $request->input('jurusan_ids', $request->input('major_ids', []));
        if (! empty($jurusanIds)) {
            Jurusan::whereIn('id', $jurusanIds)->update(['kelompok_kuota_id' => $group->id]);
        }

        $service->clearCache();

        return back()->with('success', "Kelompok kuota '{$group->nama}' berhasil ditambahkan.");
    }

    public function storeGroup(StoreKelompokKuotaRequest $request, PersyaratanMagangService $service): RedirectResponse
    {
        return $this->storeKelompok($request, $service);
    }

    /**
     * Perbarui data kelompok kuota & sinkronkan jurusan di dalamnya.
     */
    public function updateKelompok(
        StoreKelompokKuotaRequest $request,
        KelompokKuota $group,
        PersyaratanMagangService $service
    ): RedirectResponse {
        $group->update([
            'nama'         => $request->input('nama', $request->input('name')),
            'kuota'        => (int) $request->input('kuota', $request->input('quota')),
            'aktif'        => (int) $request->input('aktif', $request->input('active')),
            'is_catch_all' => (bool) $request->input('is_catch_all', false),
            'urutan'       => (int) $request->input('urutan', $request->input('sort_order', $group->urutan)),
        ]);

        $jurusanIds = $request->input('jurusan_ids', $request->input('major_ids', []));

        // Lepaskan jurusan lama yang tidak lagi dipilih
        Jurusan::where('kelompok_kuota_id', $group->id)
            ->whereNotIn('id', $jurusanIds)
            ->update(['kelompok_kuota_id' => null]);

        // Masukkan jurusan yang baru dipilih
        if (! empty($jurusanIds)) {
            Jurusan::whereIn('id', $jurusanIds)->update(['kelompok_kuota_id' => $group->id]);
        }

        $service->clearCache();

        return back()->with('success', "Kelompok kuota '{$group->nama}' berhasil diperbarui.");
    }

    public function updateGroup(StoreKelompokKuotaRequest $request, KelompokKuota $group, PersyaratanMagangService $service): RedirectResponse
    {
        return $this->updateKelompok($request, $group, $service);
    }

    /**
     * Hapus kelompok kuota (jurusan di dalamnya otomatis menjadi belum dikelompokkan).
     */
    public function destroyKelompok(
        KelompokKuota $group,
        PersyaratanMagangService $service
    ): RedirectResponse {
        $nama = $group->nama;
        $group->delete();

        $service->clearCache();

        return back()->with('success', "Kelompok '{$nama}' berhasil dihapus. Jurusan di dalamnya kini berstatus belum dikelompokkan.");
    }

    public function destroyGroup(KelompokKuota $group, PersyaratanMagangService $service): RedirectResponse
    {
        return $this->destroyKelompok($group, $service);
    }

    /**
     * Tombol cepat +/- untuk mengubah mahasiswa magang aktif.
     */
    public function quickActive(
        Request $request,
        KelompokKuota $group,
        PersyaratanMagangService $service
    ): JsonResponse|RedirectResponse {
        $delta = (int) $request->input('delta', 0);
        $newActive = $group->aktif + $delta;

        if ($newActive < 0) {
            $msg = 'Jumlah magang aktif tidak boleh kurang dari 0.';
            return $request->wantsJson()
                ? response()->json(['ok' => false, 'message' => $msg], 422)
                : back()->with('error', $msg);
        }

        if ($newActive > $group->kuota) {
            $msg = 'Jumlah magang aktif tidak boleh melebihi kuota maksimal (' . $group->kuota . ').';
            return $request->wantsJson()
                ? response()->json(['ok' => false, 'message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $group->update(['aktif' => $newActive]);
        $service->clearCache();

        if ($request->wantsJson()) {
            return response()->json([
                'ok'            => true,
                'active'        => $group->aktif,
                'aktif'         => $group->aktif,
                'remaining'     => $group->sisa,
                'sisa'          => $group->sisa,
                'percentage'    => $group->persentase,
                'persentase'    => $group->persentase,
                'status_color'  => $group->status_color,
                'is_full'       => $group->is_full,
                'message'       => 'Kuota aktif berhasil diperbarui.',
            ]);
        }

        return back()->with('success', 'Kuota aktif berhasil diperbarui.');
    }

    /**
     * Tambah jurusan baru.
     */
    public function storeJurusan(
        StoreJurusanRequest $request,
        PersyaratanMagangService $service
    ): RedirectResponse {
        $jurusan = Jurusan::create([
            'nama'              => trim($request->input('nama', $request->input('name'))),
            'kelompok_kuota_id' => $request->input('kelompok_kuota_id', $request->input('quota_group_id')) ?: null,
        ]);

        $service->clearCache();

        return back()->with('success', "Jurusan '{$jurusan->nama}' berhasil ditambahkan.");
    }

    public function storeMajor(StoreJurusanRequest $request, PersyaratanMagangService $service): RedirectResponse
    {
        return $this->storeJurusan($request, $service);
    }

    /**
     * Perbarui nama atau kelompok jurusan.
     */
    public function updateJurusan(
        StoreJurusanRequest $request,
        Jurusan $major,
        PersyaratanMagangService $service
    ): RedirectResponse {
        $major->update([
            'nama'              => trim($request->input('nama', $request->input('name'))),
            'kelompok_kuota_id' => $request->input('kelompok_kuota_id', $request->input('quota_group_id')) ?: null,
        ]);

        $service->clearCache();

        return back()->with('success', "Jurusan '{$major->nama}' berhasil diperbarui.");
    }

    public function updateMajor(StoreJurusanRequest $request, Jurusan $major, PersyaratanMagangService $service): RedirectResponse
    {
        return $this->updateJurusan($request, $major, $service);
    }

    /**
     * Hapus jurusan.
     */
    public function destroyJurusan(
        Jurusan $major,
        PersyaratanMagangService $service
    ): RedirectResponse {
        $nama = $major->nama;
        $major->delete();

        $service->clearCache();

        return back()->with('success', "Jurusan '{$nama}' berhasil dihapus.");
    }

    public function destroyMajor(Jurusan $major, PersyaratanMagangService $service): RedirectResponse
    {
        return $this->destroyJurusan($major, $service);
    }

    /**
     * Pindahkan jurusan antar kelompok (mendukung drag & drop SortableJS dan pemilih di HP).
     */
    public function pindahJurusan(
        Request $request,
        PersyaratanMagangService $service
    ): JsonResponse {
        $jurusanId = $request->input('jurusan_id', $request->input('major_id'));
        $targetGroupId = $request->input('target_group_id', $request->input('kelompok_kuota_id'));

        $jurusan = Jurusan::findOrFail($jurusanId);

        if ($targetGroupId === 'unassigned' || empty($targetGroupId)) {
            $jurusan->kelompok_kuota_id = null;
        } else {
            $targetGroup = KelompokKuota::findOrFail($targetGroupId);
            $jurusan->kelompok_kuota_id = $targetGroup->id;
        }

        $jurusan->save();
        $service->clearCache();

        return response()->json([
            'ok'      => true,
            'message' => "Jurusan '{$jurusan->nama}' berhasil dipindahkan.",
        ]);
    }

    public function reorderMajors(Request $request, PersyaratanMagangService $service): JsonResponse
    {
        return $this->pindahJurusan($request, $service);
    }
}
