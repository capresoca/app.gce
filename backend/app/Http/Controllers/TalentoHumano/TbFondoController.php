<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TalentoHumano\TbFondo;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\TalentoHumano\TbClaseFondo;

class TbFondoController extends Controller

{
    public function index()
    {     
        if (Input::get('per_page')) {
            $fondo = TbFondo::pimp()->
                              join('tb_clase_fondo', 'tb_fondo.clase_fondo', '=', 'tb_clase_fondo.clase_fondo')->
                              select('tb_clase_fondo.descripcion as descripciontcf','tb_fondo.fondo','tb_fondo.descripcion','tb_fondo.nit','tb_fondo.digito_verificacion','tb_fondo.codigo','tb_fondo.direccion','tb_fondo.telefono','tb_fondo.clase_fondo')->
                              where('tb_clase_fondo.tipo_fondo', '=', Input::get('segm') )->
                              orderBy('codigo', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($fondo);
        }
        return Resource::collection(TbFondo::pimp()->orderBy('codigo', 'desc')->get());
    }

    public function store(Request $request)
    {
        $fondo = new TbFondo();
        $fondo->codigo = $request->codigo;
        $fondo->descripcion = $request->descripcion;
        $fondo->digito_verificacion = $request->digito_verificacion;
        $fondo->direccion = $request->direccion;
        $fondo->clase_fondo = $request->clase_fondo;
        $fondo->nit = $request->nit;
        $fondo->telefono = $request->telefono;
        $fondo->save();
    }

    public function actualizar (Request $request, $id)
    {
        \Log::debug('Test var fails' . $request);
        $fondo = TbFondo::find($id);
        $fondo->codigo = $request->codigo;
        $fondo->descripcion = $request->descripcion;
        $fondo->digito_verificacion = $request->digito_verificacion;
        $fondo->direccion = $request->direccion;
        $fondo->clase_fondo = $request->clase_fondo;
        $fondo->nit = $request->nit;
        $fondo->telefono = $request->telefono;
        $fondo->save();
    }

    public function destroy($id)
    {
        try {
            $fondo = TbFondo::find($id);
            $fondo->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
