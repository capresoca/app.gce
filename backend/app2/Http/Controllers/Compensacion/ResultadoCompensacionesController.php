<?php

namespace App\Http\Controllers\Compensacion;

use App\Http\Requests\Compensacion\ResultadoCompesancionRequest;
use App\Jobs\ProcesarResultadoCompensacion;
use App\Models\Compensacion\CpResultadosCompensacion;
use App\Models\General\GnArchivo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ResultadoCompensacionesController extends Controller
{

    public function index()
    {
        return Resource::collection(
            CpResultadosCompensacion::with('abx','acx')
                ->orderBy('id','desc')
                ->paginate(Input::get('per_page'))
        );
    }

    public function store(ResultadoCompesancionRequest $request)
    {

        $archivo_abx = $this->subirStorage($request->file('abx'));
        $archivo_acx = $this->subirStorage($request->file('acx'));

        $resultado_compensacion = CpResultadosCompensacion::create(
            [
                'archivo_abx_id' => $archivo_abx ? $archivo_abx->id : null,
                'archivo_acx_id' => $archivo_acx ? $archivo_acx->id : null,
                'fecha_resultado'=> $request->fecha_resultado,
                'estado' => 'Procesando'
            ]
        );

        ProcesarResultadoCompensacion::dispatch($resultado_compensacion)->onQueue('archivos');

    }

    private function subirStorage($file)
    {
        if(!$file){
            return null;
        }
        $mime = $file->getClientMimeType();

        if($mime === 'application/zip'){
            $ruta =  Storage::disk('local')->extractTo('Compensacion/resultados', $file->path());
        }

        if($mime === 'text/plain'){
            $ruta = $file->disk('local')->storeAs('Compensacion/resultados', $file->getClientOriginalName());
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
