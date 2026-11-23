<?php

namespace Tests\Feature;

use App\Models\PermohonanDokumen;
use App\Models\PermohonanMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermohonanDokumenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'mahasiswa', 'guard_name' => 'web']);
    }

    public function test_admin_can_preview_and_stream_document(): void
    {
        Storage::fake('public');
        $fakeFile = UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf');
        $path = $fakeFile->store('dokumen_pendaftaran', 'public');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $permohonan = PermohonanMagang::create([
            'user_id' => $admin->id,
            'tanggal_mulai_rencana' => now(),
            'tanggal_selesai_rencana' => now()->addMonths(3),
            'status' => 'submitted',
        ]);
        $dokumen = PermohonanDokumen::create([
            'permohonan_id' => $permohonan->id,
            'nama_asli'     => 'laporan.pdf',
            'path'          => $path,
            'jenis'         => 'dokumen_persyaratan',
            'tipe_file'     => 'application/pdf',
            'ukuran'        => 1024,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.permohonan.dokumen', [$permohonan, $dokumen]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'inline; filename="laporan.pdf"');
    }

    public function test_admin_can_download_document(): void
    {
        Storage::fake('public');
        $fakeFile = UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf');
        $path = $fakeFile->store('dokumen_pendaftaran', 'public');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $permohonan = PermohonanMagang::create([
            'user_id' => $admin->id,
            'tanggal_mulai_rencana' => now(),
            'tanggal_selesai_rencana' => now()->addMonths(3),
            'status' => 'submitted',
        ]);
        $dokumen = PermohonanDokumen::create([
            'permohonan_id' => $permohonan->id,
            'nama_asli'     => 'laporan.pdf',
            'path'          => $path,
            'jenis'         => 'dokumen_persyaratan',
            'tipe_file'     => 'application/pdf',
            'ukuran'        => 1024,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.permohonan.dokumen', [$permohonan, $dokumen, 'download' => 1]));

        $response->assertStatus(200);
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
    }

    public function test_non_admin_cannot_access_document(): void
    {
        Storage::fake('public');
        $fakeFile = UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf');
        $path = $fakeFile->store('dokumen_pendaftaran', 'public');

        $user = User::factory()->create();
        $user->assignRole('mahasiswa');

        $permohonan = PermohonanMagang::create([
            'user_id' => $user->id,
            'tanggal_mulai_rencana' => now(),
            'tanggal_selesai_rencana' => now()->addMonths(3),
            'status' => 'submitted',
        ]);
        $dokumen = PermohonanDokumen::create([
            'permohonan_id' => $permohonan->id,
            'nama_asli'     => 'laporan.pdf',
            'path'          => $path,
            'jenis'         => 'dokumen_persyaratan',
            'tipe_file'     => 'application/pdf',
            'ukuran'        => 1024,
        ]);

        $response = $this->actingAs($user)->get(route('admin.permohonan.dokumen', [$permohonan, $dokumen]));
        $response->assertStatus(403);
    }
}
