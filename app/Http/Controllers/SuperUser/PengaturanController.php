<?php

namespace App\Http\Controllers\SuperUser;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = [
            'kantor_nama'     => Pengaturan::get('kantor.nama', 'BPS Kota Surabaya'),
            'kantor_lat'      => Pengaturan::get('kantor.lat', -7.3283024),
            'kantor_lng'      => Pengaturan::get('kantor.lng', 112.7258666),
            'kantor_radius_m' => Pengaturan::get('kantor.radius_m', 1000),
            'jam_masuk_mulai' => Pengaturan::get('presensi.jam_masuk_mulai', '07:00'),
            'jam_masuk_batas' => Pengaturan::get('presensi.jam_masuk_batas', '08:15'),
            'jam_pulang_min'  => Pengaturan::get('presensi.jam_pulang_min', '16:00'),
            'timezone'        => Pengaturan::get('presensi.timezone', 'Asia/Jakarta'),
        ];

        return view('superuser.pengaturan.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'kantor_nama'     => ['required', 'string', 'max:255'],
            'kantor_lat'      => ['required', 'numeric', 'between:-90,90'],
            'kantor_lng'      => ['required', 'numeric', 'between:-180,180'],
            'kantor_radius_m' => ['required', 'integer', 'min:10', 'max:50000'],
            'jam_masuk_mulai' => ['required', 'date_format:H:i'],
            'jam_masuk_batas' => ['required', 'date_format:H:i'],
            'jam_pulang_min'  => ['required', 'date_format:H:i'],
            'timezone'        => ['required', 'string', 'max:100'],
        ]);

        Pengaturan::set('kantor.nama', $request->kantor_nama, 'string', 'kantor');
        Pengaturan::set('kantor.lat', (float) $request->kantor_lat, 'float', 'kantor');
        Pengaturan::set('kantor.lng', (float) $request->kantor_lng, 'float', 'kantor');
        Pengaturan::set('kantor.radius_m', (int) $request->kantor_radius_m, 'int', 'kantor');

        Pengaturan::set('presensi.jam_masuk_mulai', $request->jam_masuk_mulai, 'string', 'presensi');
        Pengaturan::set('presensi.jam_masuk_batas', $request->jam_masuk_batas, 'string', 'presensi');
        Pengaturan::set('presensi.jam_pulang_min', $request->jam_pulang_min, 'string', 'presensi');
        Pengaturan::set('presensi.timezone', $request->timezone, 'string', 'presensi');

        return redirect()->route('superuser.pengaturan.index')
            ->with('success', 'Pengaturan sistem, koordinat kantor, dan presensi berhasil diperbarui.');
    }
}
