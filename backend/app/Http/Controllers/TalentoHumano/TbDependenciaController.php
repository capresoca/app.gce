<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Models\TalentoHumano\TbDependencia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

/**
 * @author Jorge Hernández 27/04/2020
 * Creando Soluciones Informaticas Ltda
 */

class TbDependenciaController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            return Resource::collection(TbDependencia::with('tbarea')->pimp()->paginate(Input::get('per_page')));
        }
        return Resource::collection(TbDependencia::with('tbarea')->pimp()->get());
    }

    public function store(Request $request)
    {
        $dependencia = new TbDependencia();
        $dependencia->fill($request->all());
        $dependencia->save();

        return response()->json([
            'data' => collect($dependencia->load('tbarea')),
            'msg' => 'se registro éxitosamente.'
        ], 201);
    }

    public function show(TbDependencia $dependencia)
    {
        return collect($dependencia->load('tbarea'));
    }

    public function update(Request $request, $dependencia)
    {
        $tbArea = TbDependencia::find($dependencia);
        $tbArea->fill($request->all());
        $tbArea->save();

        return response()->json([
            'data' => collect($tbArea),
            'msg' => 'se actualizó éxitosamente.'
        ], 201);
    }

    public function destroy(TbDependencia $dependencia)
    {
        $dependencia->delete();

        return response()->json([
            'data' => '',
            'msg' => 'se elimino éxitosamente'
        ], 200);
    }
}
