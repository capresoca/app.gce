<?php

namespace App\Http\Controllers\Compensacion;

use App\Capresoca\Compensacion\ProcesadorAportes;
use App\Http\Requests\Aseguramiento\ArchivoreporteRequest;
use App\Http\Requests\Compensacion\ArchivosaporteRequest;
use App\Http\Resources\Compensacion\ArchivosaporteResource;
use App\Jobs\ProcesarAportes;
use App\Models\Compensacion\CpArchivosaporte;
use App\Models\General\GnArchivo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ArchivoaporteController extends Controller
{

    public function index()
    {
            return ArchivosaporteResource::collection(
                CpArchivosaporte::with('archivoPila','archivoFinanciero')
                    ->orderBy('id','desc')
                    ->paginate(Input::get('per_page'))
            );


    }

    public function store(ArchivosaporteRequest $request)
    {
        $archivo_pila = $this->subirStorage($request->file('pila'));
        $archivo_financiero = $this->subirStorage($request->file('financiero'));

        $archivo_aportes = CpArchivosaporte::create(
            [
                'archivo_financiero_id' => $archivo_financiero->id,
                'archivo_pila_id' => $archivo_pila->id,
                'estado' => 'Procesando'
            ]
        );

        ProcesarAportes::dispatch($archivo_aportes)->onQueue('archivos');
        $archivo_aportes->load('archivoPila','archivoFinanciero');
        return new ArchivosaporteResource($archivo_aportes);

    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    private function subirStorage($file)
    {
        $mime = $file->getClientMimeType();

        if($mime === 'application/zip'){

            $ruta =  Storage::extractTo('Compensacion/pila', $file->path());
        }

        if($mime === 'text/plain'){
            $ruta = $file->storeAs('Compensacion/pila', $file->getClientOriginalName());
        }

        $archivo = GnArchivo::create([
           'ruta' => $ruta,
            'nombre' => $file->getClientOriginalName(),
            'size' => $file->getClientSize(),
            'extension' => $file->extension()
        ]);

        return $archivo;
    }
}
