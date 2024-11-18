<?php

namespace App\Http\Controllers\OficinaJuridica;

use App\Models\Aseguramiento\AsAfiliado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class AfiliadosTutelaController extends Controller
{
    public function byAfiliado(AsAfiliado $afiliado)
    {
        $tutelas = $afiliado->tutelas;
        return Resource::collection($tutelas);
    }
}
