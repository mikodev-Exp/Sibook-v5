<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PerizinanController extends Controller
{
    public function index()
    {
        return view('admin.perizinan.index');
    }
}