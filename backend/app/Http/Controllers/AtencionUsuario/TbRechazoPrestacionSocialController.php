<?php

namespace App\Http\Controllers\AtencionUsuario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Http\Controllers\Controller;
use App\Models\AtencionUsuario\TbRechazoPrestacionSocial;
use Illuminate\Support\Facades\Input;

class TbRechazoPrestacionSocialController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $rechazo = TbRechazoPrestacionSocial::pimp()->orderBy('consecutivo_rechazo', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($rechazo);
        }
        return Resource::collection(TbRechazoPrestacionSocial::pimp()->orderBy('consecutivo_rechazo', 'desc')->get());
    }

    public function store(Request $request)
    {
        $rechazo = new TbRechazoPrestacionSocial();
        $rechazo->descripcion = $request->descripcion;
        $rechazo->codigo_interno = $request->codigo_interno;
        $rechazo->sw_activo = $request->sw_activo;
        $rechazo->save();
    }

    public function actualizar (Request $request, $id)
    {
        $rechazo = TbRechazoPrestacionSocial::find($id);
        $rechazo->descripcion = $request->descripcion;
        $rechazo->codigo_interno = $request->codigo_interno;
        $rechazo->sw_activo = $request->sw_activo;
        $rechazo->save();
    }

    public function destroy($id)
    {
        try {
            $rechazo = TbRechazoPrestacionSocial::find($id);
            $rechazo->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
