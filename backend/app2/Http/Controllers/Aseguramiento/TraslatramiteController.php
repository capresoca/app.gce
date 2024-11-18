<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsTraslatramite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class TraslatramiteController extends Controller
{
    public function show(AsAfiliado $afiliado) {
        $traslatramites = $afiliado->traslatramites()->with('tramite.gsUsuario')->get();

        return new Resource($traslatramites);
    }
}
