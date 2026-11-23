<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\KelompokKuota;
use App\Models\PengaturanMagang;
use App\Models\User;
use App\Services\PersyaratanMagangService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PersyaratanMagangTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $this->adminUser = User::factory()->create([
            'email' => 'admin@test.com',
        ]);
        $this->adminUser->syncRoles(['admin']);
    }

    /**
     * Test: Mahasiswa magang aktif tidak boleh melebihi kuota maksimal.
     */
    public function test_active_cannot_exceed_quota_when_creating_or_updating_group(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Coba buat kelompok dengan aktif > kuota -> harus gagal validasi
        $response = $this->post(route('admin.persyaratan-magang.kelompok.store'), [
            'nama'  => 'Kelompok IT',
            'kuota' => 10,
            'aktif' => 15, // Melebihi kuota
        ]);

        $response->assertSessionHasErrors('aktif');
        $this->assertDatabaseMissing('kelompok_kuotas', ['nama' => 'Kelompok IT']);

        // 2. Buat kelompok valid
        $responseValid = $this->post(route('admin.persyaratan-magang.kelompok.store'), [
            'nama'  => 'Kelompok IT',
            'kuota' => 10,
            'aktif' => 8,
        ]);

        $responseValid->assertSessionHasNoErrors();
        $group = KelompokKuota::where('nama', 'Kelompok IT')->first();
        $this->assertNotNull($group);
        $this->assertEquals(2, $group->sisa);
        $this->assertEquals(2, $group->remaining);

        // 3. Coba update via quick active melebihi kuota
        $quickResponse = $this->postJson(route('admin.persyaratan-magang.kelompok.aktif', $group), [
            'delta' => 5, // 8 + 5 = 13 > 10
        ]);

        $quickResponse->assertStatus(422);
        $this->assertEquals(8, $group->fresh()->aktif);
    }

    /**
     * Test: Hanya boleh ada satu kelompok penampung umum (is_catch_all).
     */
    public function test_only_one_catch_all_group_is_allowed(): void
    {
        $this->actingAs($this->adminUser);

        KelompokKuota::create([
            'nama'         => 'Lainnya 1',
            'kuota'        => 10,
            'aktif'        => 2,
            'is_catch_all' => true,
        ]);

        // Coba buat kelompok kedua dengan is_catch_all = 1
        $response = $this->post(route('admin.persyaratan-magang.kelompok.store'), [
            'nama'         => 'Lainnya 2',
            'kuota'        => 10,
            'aktif'        => 2,
            'is_catch_all' => 1,
        ]);

        $response->assertSessionHasErrors('is_catch_all');
        $this->assertDatabaseMissing('kelompok_kuotas', ['nama' => 'Lainnya 2']);
    }

    /**
     * Test: Perpindahan jurusan antar kelompok dan ke belum dikelompokkan.
     */
    public function test_major_can_be_reordered_between_groups(): void
    {
        $this->actingAs($this->adminUser);

        $groupA = KelompokKuota::create(['nama' => 'Kelompok A', 'kuota' => 20, 'aktif' => 5]);
        $groupB = KelompokKuota::create(['nama' => 'Kelompok B', 'kuota' => 20, 'aktif' => 5]);

        $major = Jurusan::create(['nama' => 'Sistem Informasi', 'kelompok_kuota_id' => $groupA->id]);

        $this->assertEquals($groupA->id, $major->kelompok_kuota_id);

        // Pindahkan ke Group B
        $responseB = $this->postJson(route('admin.persyaratan-magang.jurusan.pindah'), [
            'jurusan_id'      => $major->id,
            'target_group_id' => $groupB->id,
        ]);

        $responseB->assertStatus(200);
        $this->assertEquals($groupB->id, $major->fresh()->kelompok_kuota_id);

        // Pindahkan ke 'unassigned' (belum dikelompokkan)
        $responseUnassigned = $this->postJson(route('admin.persyaratan-magang.jurusan.pindah'), [
            'jurusan_id'      => $major->id,
            'target_group_id' => 'unassigned',
        ]);

        $responseUnassigned->assertStatus(200);
        $this->assertNull($major->fresh()->kelompok_kuota_id);
    }

    /**
     * Test: Jurusan yang belum dikelompokkan otomatis tampil di kelompok "Lainnya" pada landing page.
     */
    public function test_unassigned_majors_fallback_to_catch_all_group_on_landing(): void
    {
        $catchAll = KelompokKuota::create([
            'nama'         => 'Lainnya',
            'kuota'        => 10,
            'aktif'        => 2,
            'is_catch_all' => true,
        ]);

        // Jurusan tanpa kelompok (kelompok_kuota_id = null)
        $unassignedMajor = Jurusan::create([
            'nama'              => 'Desain Komunikasi Visual',
            'kelompok_kuota_id' => null,
        ]);

        $service = new PersyaratanMagangService();
        $landingData = $service->getLandingData();

        $catchAllInLanding = collect($landingData['groups'])->firstWhere('is_catch_all', true);

        $this->assertNotNull($catchAllInLanding);
        $majorsList = is_array($catchAllInLanding) ? ($catchAllInLanding['majors'] ?? []) : ($catchAllInLanding->majors ?? []);
        $majorNames = collect($majorsList)->pluck('name')->toArray();

        $this->assertContains('Desain Komunikasi Visual', $majorNames);
    }

    /**
     * Test: Cache landing page di-forget saat admin memperbarui pengaturan.
     */
    public function test_cache_is_cleared_when_admin_updates_settings(): void
    {
        $this->actingAs($this->adminUser);

        // Panggil service untuk isi cache
        $service = new PersyaratanMagangService();
        $service->getLandingData();

        $this->assertTrue(Cache::has(PersyaratanMagangService::CACHE_KEY));

        // Update settings via controller endpoint
        $response = $this->put(route('admin.persyaratan-magang.pengaturan.update'), [
            'status'      => 'open',
            'persyaratan' => ['Syarat 1 Diperbarui', 'Syarat 2 Baru'],
        ]);

        $response->assertSessionHasNoErrors();

        // Cache harus sudah terhapus
        $this->assertFalse(Cache::has(PersyaratanMagangService::CACHE_KEY));
    }
}
