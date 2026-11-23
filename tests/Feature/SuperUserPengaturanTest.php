<?php

namespace Tests\Feature;

use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperUserPengaturanTest extends TestCase
{
    use RefreshDatabase;

    protected User $superUser;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'super_user', 'guard_name' => 'web']);

        $this->superUser = User::factory()->create();
        $this->superUser->assignRole('super_user');

        Pengaturan::set('kantor.nama', 'BPS Kota Surabaya', 'string', 'kantor');
        Pengaturan::set('kantor.lat', -7.3283024, 'float', 'kantor');
        Pengaturan::set('kantor.lng', 112.7258666, 'float', 'kantor');
        Pengaturan::set('kantor.radius_m', 1000, 'int', 'kantor');
        Pengaturan::set('presensi.jam_masuk_mulai', '07:00', 'string', 'presensi');
        Pengaturan::set('presensi.jam_masuk_batas', '08:15', 'string', 'presensi');
        Pengaturan::set('presensi.jam_pulang_min', '16:00', 'string', 'presensi');
        Pengaturan::set('presensi.timezone', 'Asia/Jakarta', 'string', 'presensi');
    }

    public function test_superuser_can_view_pengaturan_page(): void
    {
        $response = $this->actingAs($this->superUser)
            ->get(route('superuser.pengaturan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('superuser.pengaturan.index');
        $response->assertSee('BPS Kota Surabaya');
        $response->assertSee('-7.3283024');
        $response->assertSee('112.7258666');
    }

    public function test_superuser_can_update_pengaturan(): void
    {
        $response = $this->actingAs($this->superUser)
            ->put(route('superuser.pengaturan.update'), [
                'kantor_nama'     => 'BPS Kota Surabaya Baru',
                'kantor_lat'      => -7.3000000,
                'kantor_lng'      => 112.7500000,
                'kantor_radius_m' => 1500,
                'jam_masuk_mulai' => '07:15',
                'jam_masuk_batas' => '08:30',
                'jam_pulang_min'  => '16:30',
                'timezone'        => 'Asia/Jakarta',
            ]);

        $response->assertRedirect(route('superuser.pengaturan.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('BPS Kota Surabaya Baru', Pengaturan::get('kantor.nama'));
        $this->assertEquals(-7.3000000, Pengaturan::get('kantor.lat'));
        $this->assertEquals(112.7500000, Pengaturan::get('kantor.lng'));
        $this->assertEquals(1500, Pengaturan::get('kantor.radius_m'));
        $this->assertEquals('07:15', Pengaturan::get('presensi.jam_masuk_mulai'));
        $this->assertEquals('08:30', Pengaturan::get('presensi.jam_masuk_batas'));
        $this->assertEquals('16:30', Pengaturan::get('presensi.jam_pulang_min'));
    }
}
