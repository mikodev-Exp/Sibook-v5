<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PresensiController extends Controller
{
    public function index()
    {
        return view('admin.presensi.index');
    }
}