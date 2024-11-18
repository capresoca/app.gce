<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TbVigenciaTraslado;
use App\Models\General\GnBarrio;
use App\Models\Aseguramiento\BDUA\LogMsEncabezado;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TbVigenciaTrasladoController extends Controller
{
    public function index()
    {
        
        if (Input::get('per_page')) {
            $vigencia = TbVigenciaTraslado::pimp()->with('periodoLiquidacion')->orderBy('fecha_inicio', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($vigencia);
        }
        
        $datos = Resource::collection(TbVigenciaTraslado::pimp()->orderBy('fecha_inicio', 'desc')->get());
        // Log::info('Entra en el buscador: ', array($datos));
        return $datos;
    }
    
    public function logTraslado(Request $request) {
        //Log::info('Request: ', array($request));
        Log::info('Vigencia: ', array($request->vigencia));
        $resultados     = LogMsEncabezado::where('consecutivo_vigencia',$request->vigencia);
        Log::info('Palabra que Busca: ', array($request->search));
        if(!empty($request->search)) {
            $resultados     = $resultados->where('descripcion','like','%'.$request->search.'%')->get();
        } else {
            $resultados     = array();
        }
        Log::info('Request: ', array($resultados));
        return Resource::collection($resultados);
    }

    public function store(Request $request)
    {
        $vigencia = new TbVigenciaTraslado();
        $vigencia->descripcion = $request->descripcion;
        $vigencia->fecha_inicio = $request->fecha_inicio;
        $vigencia->fecha_fin = $request->fecha_fin;
        if ($request->tipo === 'Novedades'){
            $Tipo = '1';
        } else {
            $Tipo = '2';
        }
        $vigencia->tipo = $Tipo;
        $vigencia->fecha_minima_novedad = $request->fecha_minima_novedad;
        $vigencia->fecha_minima_afiliacion = $request->fecha_minima_afiliacion;
        if ($request->swabierto === 'Si') {
            $SW = '1';
        } else {
            $SW = '0';
        }
        $vigencia->sw_abierto = $SW;
        $vigencia->consecutivo_liquidacion = $request->consecutivo_liquidacion;
        $vigenciaPrevia = TbVigenciaTraslado::where('tipo', $Tipo)->where('fecha_inicio', '<=', $request->fecha_inicio)->where('fecha_fin', '>=', $request->fecha_fin)->get();
        if (count($vigenciaPrevia) === 0) {
            $vigencia->save();
            return response()->json([
                'message' => 'Todo correcto.',
                'tipo' => 'exitoso'
            ]);
        } else {
            return response()->json([
                'message' => 'Inconsistencias de fecha inicio y/o final.',
                'tipo' => 'error'
            ]);
        }
    }

    public function actualizar (Request $request, $id)
    {
        $vigencia = TbVigenciaTraslado::find($id);
        $vigencia->descripcion = $request->descripcion;
        $vigencia->fecha_inicio = $request->fecha_inicio;
        $vigencia->fecha_fin = $request->fecha_fin;
        if ($request->tipo === 'Novedades'){
            $Tipo = '1';
        } else {
            $Tipo = '2';
        }
        $vigencia->tipo = $Tipo;
        $vigencia->fecha_minima_novedad = $request->fecha_minima_novedad;
        $vigencia->fecha_minima_afiliacion = $request->fecha_minima_afiliacion;
        if ($request->swabierto === 'Si') {
            $SW = '1';
        } else {
            $SW = '0';
        }
        $vigencia->sw_abierto = $SW;
        $vigencia->consecutivo_liquidacion = $request->consecutivo_liquidacion;
        $vigenciaPrevia = TbVigenciaTraslado::where('consecutivo_vigencia','!=', $id)->where('tipo', $Tipo)->where('fecha_inicio', '<=', $request->fecha_inicio)->where('fecha_fin', '>=', $request->fecha_fin)->get();
        if (count($vigenciaPrevia) === 0) {
            $vigencia->save();
            return response()->json([
                'message' => 'Se ha actualizado correctamente.',
                'tipo' => 'exitoso'
            ]);
        } else {
            return response()->json([
                'message' => 'Inconsistencias de fecha inicio y/o final.',
                'tipo' => 'error'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            TbVigenciaTraslado::where('consecutivo_vigencia', $id)->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
