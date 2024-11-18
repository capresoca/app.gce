<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorRespuestas;
use App\Http\Requests\Aseguramiento\BDUARespuestaRequest;
use App\Http\Resources\Aseguramiento\BDUARespuestaResource;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\Aseguramiento\AsTipbduaarchivo;
use App\Models\Aseguramiento\RespuestaDBUA;
use App\Traits\ToolsTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Nexmo\Response;

class BDUARespuestaController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return BDUARespuestaResource::collection(
                AsBduarespuesta::with('archivo')->pimp()
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return BDUARespuestaResource::collection(
            AsBduarespuesta::with('archivo')
                ->pimp()
                ->orderBy('updated_at', 'desc')
                ->get()
        );

    }

    public function respuestas()
    {
        if (Input::get('per_page')) {
            return Resource::collection(
                RespuestaDBUA::pimp()->orderBy('updated_at', 'desc')
                    ->with('usuario')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            RespuestaDBUA::pimp()->with('usuario')->orderBy('updated_at', 'desc')
                ->get()
        );
    }

    public function store(BDUARespuestaRequest $request)
    {
        try {
            $proceso = AsBduaproceso::findOrFail($request->as_bduaproceso_id);
            $procesador = new ProcesadorRespuestas($request->file('files'), $proceso);
            return $procesador->procesarArchivos();
        } catch (\Exception $e) {
            return $e;
        }

    }

    public function cargarRespuesta(Request $request)
    {
        if(!ToolsTrait::checkCollation($request->file('file')->get(),'UTF-8')){
            return response()->json(['message' => 'Favor convertir a codificación UTF-8'],422);
        }

        $tipExplode = explode('.',$request->tipo);
        $pre = $tipExplode[0];
        $sufijo = null;
        $sufijoArchivo = null;

        if(isset($tipExplode[1])){
            $sufijo = $tipExplode[1];
        }

        $fileName = $request->file('file')->getClientOriginalName();

        if(RespuestaDBUA::where('nombreArchivo',$fileName)->exists()){
            return response()->json(['message' => 'Este archivo ya fue procesado'],422);
        }

        if($sufijo){
            $sufijoArchivo = substr($fileName,-7,-4);
        }

        $prefijoArchivo = substr($fileName,0,strlen($pre));

        if($sufijo != $sufijoArchivo || $prefijoArchivo != $pre){
            return response()->json(['message' => 'El archivo no coicide'],422);
        }

        $tipoArchivo = AsTipbduaarchivo::where(['iniciales' => $prefijoArchivo, 'sufijo' => $sufijo])->first();

        if(!$tipoArchivo){
            return response()->json(['message' => 'Archivo no conocido'],422);
        }

        $procesador = new $tipoArchivo->clase_procesador($request);

        $procesador->procesar();

        return $procesador->getRespuestas();

    }

    public function show(AsBduarespuesta $bduarespuesta)
    {
        $bduarespuesta->load('registros.glosas', 'registros.acciones', 'pendientes');
        return new BDUARespuestaResource($bduarespuesta);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
