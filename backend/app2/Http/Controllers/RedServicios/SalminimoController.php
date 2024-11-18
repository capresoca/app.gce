<?php

namespace App\Http\Controllers\RedServicios;

use App\Models\RedServicios\RsSalminimo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalminimoController extends Controller
{
    public function index()
    {
        return RsSalminimo::orderBy('anio')->get();
    }
}
