<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Models\TalentoHumano\TbCargo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
/**
 * @author Jorge Hernández 27/04/2020
 * Creando Soluciones Informaticas Ltda
 */
class TbCargoController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return Resource::collection(TbCargo::pimp()->paginate(Input::get('per_page')));
        }
        return Resource::collection(TbCargo::pimp()->get());
    }

    public function store(Request $request)
    {
        $cargo = new TbCargo();
        $cargo->fill($request->all());
        $cargo->save();

        return response()->json([
            'data' => collect($cargo),
            'msg' => 'se registro éxitosamente.'
        ], 201);
    }

    public function show(TbCargo $cargo)
    {
        return collect($cargo);
    }

    public function update(Request $request, $cargo)
    {
        $tbcargo = TbCargo::find($cargo);
        $tbcargo->fill($request->all());
        $tbcargo->save();

        return response()->json([
            'data' => collect($tbcargo),
            'msg' => 'se actualizo éxitosamente.'
        ], 201);
    }

    public function destroy(TbCargo $cargo)
    {
        $cargo->delete();

        return response()->json([
            'data' => '',
            'msg' => 'se elimino éxitosamente'
        ], 200);
    }
}
