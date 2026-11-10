<?php

namespace App\Http\Controllers\SuperUser;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('superuser.dashboard');
    }
}