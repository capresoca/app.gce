<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\DetstrgastoRequest;
use App\Http\Requests\Presupuesto\StrgastoRequest;
use App\Models\Presupuesto\PrDetstrgasto;
use App\Models\Presupuesto\PrStrgasto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class DetstrgastoController extends Controller
{

    public function index(){
        return Resource::collection(PrDetstrgasto::orderBy('updated_at')->get());
    }

    public function store(PrStrgasto $pr_strgasto, DetstrgastoRequest $request){
        try {
            $detalle = $pr_strgasto->detalles()->create($request->all());
            $detalle->load('rubro', 'tipoGasto');
            return new Resource($detalle);
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception,
                'msg' => $exception->getMessage()
            ], 500);
        }
    }

    public function show(PrDetstrgasto $detalle) {
        return new Resource($detalle->load('rubro', 'tipoGasto'));
    }

    public function update(PrStrgasto $pr_strgasto, PrDetstrgasto $detalle, DetstrgastoRequest $request)
    {
        $detalle->update($request->all());
        $detalle->load('rubro', 'tipoGasto');
        return new Resource($detalle);
    }

    public function destroy(PrStrgasto $pr_strgasto, PrDetstrgasto $detalle)
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
