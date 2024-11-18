<?php

namespace App\Http\Controllers\AtencionUsuario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use App\Models\AtencionUsuario\TbTipoSoporte;
use App\Models\AtencionUsuario\TbProcesoAdministrativoEncabezado;
use App\Models\AtencionUsuario\TbProcesoAdministrativoDetalle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TBTipoSoportexIncapacidadController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $soporte = TbProcesoAdministrativoEncabezado::pimp()->orderBy('descripcion', 'asc')->paginate(Input::get('per_page'));
            return Resource::collection($soporte);
        }
        return Resource::collection(Tbsoporte::pimp()->orderBy('descripcion', 'asc')->get());
    }

    public function store(Request $request)
    {
        \Log::debug('prueba'.$request);
        $soporte = new TbProcesoAdministrativoEncabezado();
        $soporte->descripcion = $request[0]['descripcion'];
        $soporte->proceso = $this->tipoIncapacidad($request[0]['proceso']);
        $soporte->save();

        $this->storeDetalles($request[1], $soporte->consecutivo_proceso);

        return response()->json([
            'message' => 'Creado con exito',
            'data' => TbProcesoAdministrativoEncabezado::where('consecutivo_proceso',$soporte->consecutivo_proceso)->first()
        ]);
    }

    public function actualizar (Request $request, $id)
    {
        $soporte = TbProcesoAdministrativoEncabezado::find($id);
        $soporte->descripcion = $request[0]['descripcion'];
        $soporte->proceso = $this->tipoIncapacidad($request[0]['proceso']);
        $soporte->save();
        return response()->json([
            'message' => 'Editado con exito',
            'data' => $soporte
        ]);
    }

    public function destroy($id)
    {
        try {
            $soporte = TbProcesoAdministrativoEncabezado::find($id);
            $soporte->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }

// DETALLES
    public function storeDetalles($arrayDetalle,$idEncabezado)
    {
        foreach ($arrayDetalle as $key => $value) {
            foreach ($value as $key => $detail) {
                $detalle = new TbProcesoAdministrativoDetalle();
                $detalle->consecutivo_proceso = $idEncabezado;
                $detalle->consecutivo_soporte = $detail['consecutivo_soporte'];
                $detalle->sw_activo = $this->dicotomia($detail['sw_activo']);
                $detalle->sw_obligatorio = $this->dicotomia($detail['sw_obligatorio']);
                $detalle->save();
            }            
        }
    }

    public function dicotomia($valor)
    {
        $tipos = array(
            array("Si",1),
            array("No",2)
        );
        
        $longitud = count($tipos);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tipos[$i][0]) {
                $idtipos = $tipos[$i][1];
            }
        }

        return $idtipos;
    }

    public function tipoIncapacidad($valor)
    {
        $tipos = array(
            array("Incapacidad Enfermedad General",2),
            array("Incapacidad por Accidente de Trabajo",4),
            array("Incapacidad por Accidente de Tránsito",5),
            array("Licencia de Maternidad",7),
            array("Licencia de Paternidad",8),
            array("Licencia Fallecimiento Madre",17)
        );
        
        $longitud = count($tipos);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tipos[$i][0]) {
                $idtipos = $tipos[$i][1];
            }
        }

        return $idtipos;
    }

    public function findById($id)
    {
        $encabezado = TbProcesoAdministrativoEncabezado::where('consecutivo_proceso',$id)->first();
        $detalle = TbProcesoAdministrativoDetalle::where('consecutivo_proceso',$id)->get();
        if($encabezado){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del fondo ya se encuentra registrado',
                'data' => $encabezado,
                'detalle' => $detalle,
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'no existe'
        ],204);
    }
}
