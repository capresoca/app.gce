<?php

namespace App\Http\Controllers\AtencionUsuario;

use Illuminate\Http\Request;
use App\Models\AtencionUsuario\TbProcesoAdministrativoDetalle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class TBProcesoAdministrativoDetallesController extends Controller
{
    public function index()
    {
        return TbProcesoAdministrativoDetalle::where('consecutivo_proceso',Input::get('idProceso'))
                                            //  ->where('consecutivo_soporte',Input::get('idSoporte'))
                                             ->orderBy('consecutivo_soporte','desc')->get();                                          
    }

    public function store(Request $request)
    {
        $detalle = new TbProcesoAdministrativoDetalle();
        $detalle->consecutivo_proceso = $request->consecutivo_proceso;
        $detalle->consecutivo_soporte = $request->consecutivo_soporte;
        $detalle->sw_activo = $this->dicotomia($request->sw_activo);
        $detalle->sw_obligatorio = $this->dicotomia($request->sw_obligatorio);
        $detalle->save();
    }

    public function actualizar (Request $request, $id1, $id2)
    {
        // $detalle = TbProcesoAdministrativoDetalle::find($id);
        $detalle = TbProcesoAdministrativoDetalle::where('consecutivo_proceso',$id1)
                                                 ->where('consecutivo_soporte',$id2)
                                                 ->first();

        $detalle->consecutivo_proceso = $request->consecutivo_proceso;
        $detalle->consecutivo_soporte = $request->consecutivo_soporte;
        $activo = $this->dicotomia($request->sw_activo);
        $obligatorio = $this->dicotomia($request->sw_obligatorio);

        DB::statement(
            'UPDATE tb_proceso_administrativo_detalles 
             SET consecutivo_proceso = ' .$request->consecutivo_proceso. ',
                 consecutivo_soporte = ' .$request->consecutivo_soporte. ',
                           sw_activo = ' .$this->dicotomia($request->sw_activo). ',
                      sw_obligatorio = ' .$this->dicotomia($request->sw_obligatorio).' 
             WHERE consecutivo_proceso = '.$id1.' AND consecutivo_soporte = '.$id2
        );
        // $detalle->save();
    }

    public function destroy($id1, $id2)
    {
        TbProcesoAdministrativoDetalle::where('consecutivo_proceso',$id1)
                                      ->where('consecutivo_soporte',$id2)
                                      ->delete();
        // try {
        //     $detalle= TbProcesoAdministrativoDetalle::where('consecutivo_proceso',$id1)
        //                                             ->where('consecutivo_soporte',$id2);

        //     // $detalle = TbProcesoAdministrativoDetalle::find($id);
        //     \Log::debug('el detalle'.$detalle);
        //     // $detalle->delete();
        //     return response('',202);
        // } catch (\Exception $e) {
        //     \Log::debug('el error'.$e);
        //     return $e;
        // }
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
}
