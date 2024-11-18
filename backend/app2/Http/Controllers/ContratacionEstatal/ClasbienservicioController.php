<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Resources\ContratacionEstatal\ClasbienservicioResource;
use App\Models\ContratacionEstatal\CeClasbienservicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClasbienservicioController extends Controller
{
    public function show ($clasbienservicio) {
        $hijosClases = CeClasbienservicio::find($clasbienservicio);
        $hijosClases->load('bienservicios');
        return new ClasbienservicioResource($hijosClases);
    }
}
