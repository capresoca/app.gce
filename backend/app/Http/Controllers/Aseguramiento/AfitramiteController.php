<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Models\Aseguramiento\AsAfiliado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class AfitramiteController extends Controller
{
    public function show(AsAfiliado $afiliado)
    {
        $afitramites = $afiliado->afitramites()->with('tramite.gsUsuario')->get();
        return new Resource($afitramites);
    }
}
