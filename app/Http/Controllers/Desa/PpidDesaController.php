<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;

class PpidDesaController extends Controller
{
    public function index()
    {
        return view('desa.ppid.index');
    }
}