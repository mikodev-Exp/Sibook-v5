<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\PersyaratanMagangService;

class HomeController extends Controller
{
    public function index(PersyaratanMagangService $service)
    {
        $requirementData = $service->getLandingData();

        return view('public.home', compact('requirementData'));
    }
}