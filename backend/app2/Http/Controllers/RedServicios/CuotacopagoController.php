<?php

namespace App\Http\Controllers\RedServicios;

use App\Models\RedServicios\RsCuotacopago;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class CuotacopagoController extends Controller
{
    public function index()
    {
        $cuotacopagos = RsCuotacopago::whereHas('salminimo',function ($query){
            $query->where('anio',today()->format('Y'));
        })->with('salminimo')->get();

        return new Resource($cuotacopagos);
    }
}
