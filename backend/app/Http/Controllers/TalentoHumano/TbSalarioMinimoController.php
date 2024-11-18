<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TalentoHumano\TbSalarioMinimo;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TbSalarioMinimoController extends Controller

{
    public function index()
    {
        if (Input::get('per_page')) {
            $salario = TbSalarioMinimo::pimp()->orderBy('ano', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($salario);
        }
        return Resource::collection(TbSalarioMinimo::pimp()->orderBy('ano', 'desc')->get());
    }

    public function store(Request $request)
    {
        $salario = new TbSalarioMinimo();
        $salario->descripcion = $request->descripcion;
        $salario->ano = $request->ano;
        $salario->valor = $request->valor;
        $salario->subsidio_transporte = $request->subsidio_transporte;
        $salario->save();
    }

    public function actualizar (Request $request, $id)
    {
        $salario = TbSalarioMinimo::find($id);
        $salario->descripcion = $request->descripcion;
        $salario->ano = $request->ano;
        $salario->valor = $request->valor;
        $salario->subsidio_transporte = $request->subsidio_transporte;
        $salario->save();
    }

    public function destroy($id)
    {
        try {
            $salario = TbSalarioMinimo::find($id);
            $salario->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
