<?php

namespace App\Http\Controllers\AtencionUsuario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use App\Models\AtencionUsuario\TbTipoSoporte;
use App\Http\Controllers\Controller;

class TBTipoSoporteController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $soporte = TbTipoSoporte::pimp()->orderBy('descripcion', 'asc')->paginate(Input::get('per_page'));
            return Resource::collection($soporte);
        }
        return Resource::collection(TbTipoSoporte::pimp()->orderBy('descripcion', 'asc')->get());
    }

    public function store(Request $request)
    {
        $soporte = new TbTipoSoporte();
        $soporte->descripcion = $request->descripcion;
        $soporte->observacion = $request->observacion;
        $soporte->save();
    }

    public function actualizar (Request $request, $id)
    {
        $soporte = TbTipoSoporte::find($id);
        $soporte->descripcion = $request->descripcion;
        $soporte->observacion = $request->observacion;
        $soporte->save();
    }

    public function destroy($id)
    {
        try {
            $soporte = TbTipoSoporte::find($id);
            $soporte->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
