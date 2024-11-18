<?php

namespace App\Http\Controllers\RedServicios;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\RedServicios\RsServicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class ServicioController extends Controller
{
    public function getPrimarios()
    {
        $servicios = RsServicio::wherePrimario(1)->with('habilitados.entidad.municipio')->get();
        //$servicios = RsServicio::primarios($afiliado)->get();

        return Resource::collection($servicios);

    }



}
