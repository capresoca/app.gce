<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Models\TalentoHumano\TbArea;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

/**
 * @author Jorge Hernández 27/04/2020
 * Creando Soluciones Informaticas Ltda
 */

class TbAreaController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return Resource::collection(TbArea::with('cencosto')->pimp()->paginate(Input::get('per_page')));
        }
        return Resource::collection(TbArea::with('cencosto')->pimp()->get());
    }

    public function store(Request $request)
    {
        $tbArea = new TbArea();
        $tbArea->fill($request->all());
        $tbArea->save();

        return response()->json([
            'data' => collect($tbArea->load('cencosto')),
            'msg' => 'se registro éxitosamente.'
        ], 201);
    }

    public function show(TbArea $tbArea)
    {
        return collect($tbArea->load('cencosto'));
    }

    public function update(Request $request, $tbArea)
    {
        $tbArea = TbArea::find($tbArea);
        $tbArea->fill($request->all());
        $tbArea->save();

        return response()->json([
            'data' => collect($tbArea),
            'msg' => 'se actualizó éxitosamente.'
        ], 201);
    }

    public function destroy(TbArea $tbCentroConsto)
    {
        $tbCentroConsto->delete();

        return response()->json([
            'data' => '',
            'msg' => 'se elimino éxitosamente'
        ], 200);
    }
}
