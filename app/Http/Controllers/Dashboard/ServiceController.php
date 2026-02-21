<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('dashboard.services.index');
    }
}
