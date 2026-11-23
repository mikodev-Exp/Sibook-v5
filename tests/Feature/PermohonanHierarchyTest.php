<?php

namespace Tests\Feature;

use App\Enums\StatusMagang;
use App\Enums\StatusPermohonan;
use App\Models\Magang;
use App\Models\PermohonanMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermohonanHierarchyTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $superUser;
    protected User $pendaftar;
    protected PermohonanMagang $permohonan;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'pendaftar', 'guard_name' => 'web']);
        Role::create(['name' => 'mahasiswa', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'super_user', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->superUser = User::factory()->create();
        $this->superUser->assignRole('super_user');

        $this->pendaftar = User::factory()->create();
        $this->pendaftar->assignRole('pendaftar');

        $this->permohonan = PermohonanMagang::create([
            'user_id'                 => $this->pendaftar->id,
            'tanggal_mulai_rencana'   => now()->addDays(7),
            'tanggal_selesai_rencana' => now()->addDays(90),
            'status'                  => StatusPermohonan::Submitted,
        ]);
    }

    public function test_admin_can_review_and_mark_eligible_without_promoting_to_mahasiswa(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.permohonan.update-status', $this->permohonan), [
                'status'        => 'reviewed_by_admin',
                'catatan_admin' => 'Berkas pendaftar lengkap dan sesuai kriteria.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->permohonan->refresh();
        $this->pendaftar->refresh();

        // Status permohonan harus reviewed_by_admin
        $this->assertEquals(StatusPermohonan::ReviewedByAdmin, $this->permohonan->status);
        $this->assertEquals($this->admin->id, $this->permohonan->reviewed_by);
        $this->assertNotNull($this->permohonan->reviewed_at);
        $this->assertEquals('Berkas pendaftar lengkap dan sesuai kriteria.', $this->permohonan->catatan_admin);

        // Hierarki: Role pendaftar TIDAK boleh berubah menjadi mahasiswa
        $this->assertTrue($this->pendaftar->hasRole('pendaftar'));
        $this->assertFalse($this->pendaftar->hasRole('mahasiswa'));

        // Tidak boleh ada magang aktif yang terbuat oleh admin
        $this->assertNull(Magang::where('user_id', $this->pendaftar->id)->first());
    }

    public function test_admin_cannot_approve_permohonan_directly(): void
    {
        // Admin mencoba mengirim status 'approved'
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.permohonan.update-status', $this->permohonan), [
                'status'        => 'approved',
                'catatan_admin' => 'Admin mencoba langsung approve.',
            ]);

        // Harus gagal validasi (422 / session has errors)
        $response->assertSessionHasErrors(['status']);

        $this->permohonan->refresh();
        $this->pendaftar->refresh();

        // Status tetap submitted dan pendaftar tidak berubah
        $this->assertEquals(StatusPermohonan::Submitted, $this->permohonan->status);
        $this->assertTrue($this->pendaftar->hasRole('pendaftar'));
        $this->assertFalse($this->pendaftar->hasRole('mahasiswa'));
    }

    public function test_admin_can_reject_ineligible_applicant(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.permohonan.update-status', $this->permohonan), [
                'status'        => 'rejected',
                'catatan_admin' => 'Jurusan tidak sesuai dan IPK di bawah standar.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->permohonan->refresh();
        $this->assertEquals(StatusPermohonan::Rejected, $this->permohonan->status);
        $this->assertEquals($this->admin->id, $this->permohonan->reviewed_by);
    }

    public function test_superuser_can_approve_and_promote_pendaftar_to_mahasiswa(): void
    {
        // Admin meninjau terlebih dahulu
        $this->permohonan->update([
            'status'        => StatusPermohonan::ReviewedByAdmin,
            'reviewed_by'   => $this->admin->id,
            'reviewed_at'   => now(),
            'catatan_admin' => 'Rekomendasi layak.',
        ]);

        // Super Admin melakukan persetujuan akhir
        $response = $this->actingAs($this->superUser)
            ->post(route('superuser.permohonan.approve', $this->permohonan), [
                'catatan_superuser' => 'Disetujui untuk penugasan tim Statistik Sosial.',
            ]);

        $response->assertRedirect(route('superuser.permohonan.index'));

        $this->permohonan->refresh();
        $this->pendaftar->refresh();

        // Status permohonan harus Approved
        $this->assertEquals(StatusPermohonan::Approved, $this->permohonan->status);
        $this->assertEquals($this->superUser->id, $this->permohonan->approved_by);
        $this->assertNotNull($this->permohonan->approved_at);

        // Role pendaftar resmi menjadi mahasiswa
        $this->assertTrue($this->pendaftar->hasRole('mahasiswa'));
        $this->assertFalse($this->pendaftar->hasRole('pendaftar'));

        // Magang aktif terbuat
        $magang = Magang::where('user_id', $this->pendaftar->id)->first();
        $this->assertNotNull($magang);
        $this->assertEquals(StatusMagang::Active, $magang->status);
    }

    public function test_superuser_can_reject_applicant(): void
    {
        $response = $this->actingAs($this->superUser)
            ->post(route('superuser.permohonan.reject', $this->permohonan), [
                'catatan_superuser' => 'Kuota divisi penuh untuk periode ini.',
            ]);

        $response->assertRedirect(route('superuser.permohonan.index'));

        $this->permohonan->refresh();
        $this->assertEquals(StatusPermohonan::Rejected, $this->permohonan->status);
        $this->assertEquals($this->superUser->id, $this->permohonan->approved_by);
        $this->assertTrue($this->pendaftar->hasRole('pendaftar'));
    }

    public function test_unreviewed_applicant_does_not_appear_in_superuser_queue(): void
    {
        // Status masih 'submitted' (Menunggu Admin)
        $this->assertEquals(StatusPermohonan::Submitted, $this->permohonan->status);

        // Super Admin membuka antrean finalisasi
        $response = $this->actingAs($this->superUser)
            ->get(route('superuser.permohonan.index'));

        $response->assertStatus(200);
        // Pendaftar yang belum ditinjau admin TIDAK BOLEH muncul di antrean finalisasi Super Admin
        $response->assertDontSee($this->pendaftar->name);
        $response->assertSee('Tidak ada permohonan dalam antrean tab ini.');
    }

    public function test_superuser_cannot_approve_unreviewed_applicant(): void
    {
        // Status masih 'submitted' (belum ditinjau Admin)
        $this->assertEquals(StatusPermohonan::Submitted, $this->permohonan->status);

        // Super Admin mencoba menyetujui langsung
        $response = $this->actingAs($this->superUser)
            ->post(route('superuser.permohonan.approve', $this->permohonan), [
                'catatan_superuser' => 'Coba approve tanpa tinjauan admin.',
            ]);

        $response->assertSessionHas('error', 'Permohonan belum dapat disetujui karena belum ditinjau dan dinyatakan layak oleh Admin.');

        $this->permohonan->refresh();
        $this->pendaftar->refresh();

        // Status tetap submitted dan pendaftar tidak diubah jadi mahasiswa
        $this->assertEquals(StatusPermohonan::Submitted, $this->permohonan->status);
        $this->assertTrue($this->pendaftar->hasRole('pendaftar'));
        $this->assertFalse($this->pendaftar->hasRole('mahasiswa'));
    }
}
