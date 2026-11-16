<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PermohonanController extends Controller
{
    public function index()
    {
        return view('admin.permohonan.index');
    }
}