<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Jobs\ProcesosBDUA\ProcesarArchivosBDUA;
use App\Models\Aseguramiento\Procesos\AsS1vid;
use App\Traits\ArchivoTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class CargadorArchivosBDUAController extends Controller
{

    public function store (Request $request)
    {
//        dd($request->fecha);
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $archive = ArchivoTrait::subirArchivo($file, 'Aseguramiento/ProcesosBDUA',['text/plain']);
            $control = AsS1vid::create([
                'fechaProceso' => $request->fecha,
                'nombreArchivo' => $archive->nombre,
                'ruta_archivo' => $archive->ruta,
                'fS' => Carbon::now()->toDateString(),
            ]);

            ProcesarArchivosBDUA::dispatch($control)->onQueue('archivos');
            $control->load('usuario');
        }

        return new Resource($control);
    }



}
