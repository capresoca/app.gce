<?php

namespace App\Http\Controllers\Presupuesto;

use App\Models\Presupuesto\PrStrgasto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class PresupuestoActualController extends Controller
{
    public function __invoke()
    {
        $presupuestoGastos = PrStrgasto::with('detalles.rubro')->whereEstado('Confirmada')->orderBy('periodo','desc')->first();
        return new Resource($presupuestoGastos);
    }
}
