<?php

namespace App\Http\Controllers\AtencionUsuario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\AtencionUsuario\TbPreferencia;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class TbPreferenciaController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $preferencia = TbPreferencia::pimp()->orderBy('consecutivo_preferencia', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($preferencia);
        }
        return Resource::collection(TbPreferencia::pimp()->orderBy('consecutivo_preferencia', 'desc')->get());
    }

    public function store(Request $request)
    {
        $preferencia = new TbPreferencia();
        $preferencia->descripcion = $request->descripcion;
        $preferencia->tipo_preferencia = $request->tipo_preferencia;
        $preferencia->valor = $request->valor;
        $preferencia->valor_texto = $request->valor_texto;
        $preferencia->save();
    }

    public function actualizar (Request $request, $id)
    {
        $preferencia = TbPreferencia::find($id);
        $preferencia->descripcion = $request->descripcion;
        $preferencia->tipo_preferencia = $request->tipo_preferencia;
        $preferencia->valor = $request->valor;
        $preferencia->valor_texto = $request->valor_texto;
        $preferencia->save();
    }

    public function destroy($id)
    {
        try {
            $preferencia = TbPreferencia::find($id);
            $preferencia->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
