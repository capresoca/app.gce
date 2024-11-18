<?php

namespace App\Http\Controllers\RedServicios;

use App\Models\RedServicios\RsEntidadesBase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EntidadBaseController extends Controller
{
    public function index()
    {

        return RsEntidadesBase::pimp()->orderBy('updated_at','desc')->limit(100)->get();

    }
}
