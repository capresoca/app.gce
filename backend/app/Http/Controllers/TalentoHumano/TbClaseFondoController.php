<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TalentoHumano\TbClaseFondo;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TbClaseFondoController extends Controller

{
    public function index()
    {
        if (Input::get('per_page')) {
            $claseF = TbClaseFondo::pimp()->orderBy('clase_fondo', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($claseF);
        }
        return Resource::collection(TbClaseFondo::pimp()->orderBy('clase_fondo', 'desc')->get());
    }

    public function store(Request $request)
    {
        $claseF = new TbClaseFondo();
        $claseF->descripcion = $request->descripcion;
        if ($request->tipo_fondo === 'Fondo'){
            $Tipo = '1';
        } else {
            $Tipo = '2';
        }
        $claseF->tipo_fondo = $Tipo;
        $claseF->save();
    }

    public function actualizar (Request $request, $id)
    {
        $claseF = TbClaseFondo::find($id);
        $claseF->descripcion = $request->descripcion;
        if ($request->tipo_fondo === 'Fondo'){
            $Tipo = '1';
        } else {
            $Tipo = '2';
        }
        $claseF->tipo_fondo = $Tipo;
        $claseF->save();
    }

    public function destroy($id)
    {
        try {
            $claseF = TbClaseFondo::find($id);
            $claseF->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
