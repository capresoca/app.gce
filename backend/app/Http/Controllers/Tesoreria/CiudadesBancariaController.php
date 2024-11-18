<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Requests\Tesoreria\CiudadesBancariaRequest;
use App\Http\Resources\Tesoreria\CiudadesBancariaResource;
use App\Models\Tesoreria\TsCiudadesBancaria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CiudadesBancariaController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            $obligaciones = TsCiudadesBancaria::pimp()
                ->orderBy('updated_at', 'desc')
                ->paginate(Input::get('per_page'));
            return CiudadesBancariaResource::collection($obligaciones);
        }
        return CiudadesBancariaResource::collection(TsCiudadesBancaria::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(CiudadesBancariaRequest $request)
    {
        try {
            $ts_ciudades_bancaria = TsCiudadesBancaria::create($request->all());

            return response()->json([
                'msg' => 'Se ha registrado éxitosamente la ciudad bancaría.',
                'data' => new CiudadesBancariaResource($ts_ciudades_bancaria)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => 'Error en el servidor'
            ], 500);
        }
    }

    public function show(TsCiudadesBancaria $ts_ciudades_bancaria)
    {
        return new CiudadesBancariaResource($ts_ciudades_bancaria);
    }

    public function update(CiudadesBancariaRequest $request, TsCiudadesBancaria $ts_ciudades_bancaria)
    {
        try {
            $ts_ciudades_bancaria->update($request->all());

            return response()->json([
                'msg' => 'Se ha actualizado éxitosamente la ciudad bancaría.',
                'data' => new CiudadesBancariaResource($ts_ciudades_bancaria)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => 'Error en el servidor'
            ], 500);
        }
    }

    public function destroy($id) {}
}
