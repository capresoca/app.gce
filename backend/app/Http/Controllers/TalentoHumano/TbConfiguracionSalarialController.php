<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TalentoHumano\TbConfiguracionSalarial;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class TbConfiguracionSalarialController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $salario = TbConfiguracionSalarial::pimp()->orderBy('configuracion_salarial', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($salario);
        }
        return Resource::collection(TbConfiguracionSalarial::pimp()->orderBy('configuracion_salarial', 'desc')->get());
    }

    public function store(Request $request)
    {
        $salario = new TbConfiguracionSalarial();
        $salario->fill($request->except('sw_subsidio_transporte'));
        if ($request->sw_subsidio_transporte === 'Si'){
            $SW = '1';
        } else {
            $SW = '0';
        }
        $salario->sw_subsidio_transporte = $SW;
        $salario->save();
    }

    public function actualizar (Request $request, $id)
    {
        $salario = TbConfiguracionSalarial::find($id);
        $salario->fill($request->except('sw_subsidio_transporte'));
        if ($request->sw_subsidio_transporte === 'Si'){
            $SW = '1';
        } else {
            $SW = '0';
        }
        $salario->sw_subsidio_transporte = $SW;
        $salario->save();
    }

    public function destroy($id)
    {
        try {
            $salario = TbConfiguracionSalarial::find($id);
            $salario->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
