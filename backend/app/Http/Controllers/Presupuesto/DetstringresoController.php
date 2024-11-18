<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\DetstringresoRequest;
use App\Http\Resources\Presupuesto\DetstringresoResource;
use App\Models\Presupuesto\PrDetstringreso;
use App\Models\Presupuesto\PrStringreso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class DetstringresoController extends Controller
{
    public function index() {
        return DetstringresoResource::collection(PrDetstringreso::with('rubro', 'tipoIngreso')->orderBy('updated_at')->get());
    }

    public function store(PrStringreso $pr_stringreso, DetstringresoRequest $request)
    {
        try {
            $detalle = $pr_stringreso->detalles()->create($request->all());
            $detalle->load('rubro', 'tipoIngreso');
            return new DetstringresoResource($detalle);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function show(PrStringreso $pr_stringreso, PrDetstringreso $detalle)
    {
        return new DetstringresoResource($detalle->load('rubro', 'tipoIngreso'));
    }

    public function update(PrStringreso $pr_stringreso, PrDetstringreso $detalle, DetstringresoRequest $request)
    {
        $detalle->update($request->all());
        $detalle->load('rubro','tipoIngreso');
        return new DetstringresoResource($detalle);
    }

    public function destroy(PrStringreso $pr_stringreso, PrDetstringreso $detalle)
    {
        try{
            $detalle->delete();
            return response()->json(
                [
                    'message' => 'El el detalles se ha removido.'
                ]
            );
        }catch (\Exception $e){
            return response()->json([
                'errors' => $e,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
