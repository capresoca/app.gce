<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\ArchivosBDUA\BDUAExporter;
use App\Capresoca\Aseguramiento\GeneradorReporte;
use App\Models\Aseguramiento\AsBduaarchivo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BDUAArchivoController extends Controller
{

    public function download(AsBduaarchivo $bduaarchivo)
    {
        $bduaarchivo->load('tramites');
        $generador = new GeneradorReporte($bduaarchivo);
        return $generador->download($bduaarchivo->nombre . '.txt');
    }

    public function downloadFile()
    {
        $this->validate(request(),['tipo' => 'required|in:MS,NS,MC,NC,S1,R1,S4,R4']);

        $generador = new BDUAExporter(request()->tipo);

        return $generador->download($generador->getFileName());
    }

    public function show($id)
    {
        return response()->json([
            'archivo' => AsBduaarchivo::find($id)
        ]);
    }
}
