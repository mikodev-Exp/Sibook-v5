<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;

class UjianController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $permohonan = $user->permohonanMagang;

        $gformEsai = Pengaturan::get('ujian.gform_esai', '#');
        $gformTulis = Pengaturan::get('ujian.gform_tulis', '#');

        return view('pendaftar.ujian.index', compact('permohonan', 'gformEsai', 'gformTulis'));
    }
}