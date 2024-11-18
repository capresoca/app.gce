<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Models\ContratacionEstatal\CeEstpregarantia;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EstpregarantiasController extends Controller
{
    public function update(Request $request, CeEstpregarantia $estpregarantia)
    {
        if($request->hasFile('archivo')){
            $archivo = ArchivoTrait::subirArchivo($request->file('archivo'), 'ProcesoContractual/Polizas');
            $estpregarantia->gn_archivo = $archivo->id;
            $estpregarantia->save();
        }

    }
}
