<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\ConceptoRequest;
use App\Models\Presupuesto\PrConcepto;
use App\Http\Controllers\Controller;
use App\Models\Presupuesto\PrNiveles;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class ConceptoController extends Controller
{
    public function index ()
    {
        if(Input::get('per_page')){
            return Resource::collection(
                PrConcepto::pimp()->rubros()
                    ->orderBy('codigo')
                    ->paginate(Input::get('per_page'))
            );
        }
        return Resource::collection(PrConcepto::pimp()->rubros()->orderBy('codigo')->get());
    }

    public function arbol()
    {
        $conceptos = PrConcepto::pimp()->whereHas('nivel',function ($query){
            $query->where('nivel',1);
        })->descendencia()->get();

        return $conceptos;
    }

    public function store(ConceptoRequest $request)
    {
        $nivel = 1;

        $concepto = new PrConcepto($request->except('pr_concepto_id'));

        if((int)$request->pr_concepto_id)
        {
            $nivel = PrConcepto::find($request->pr_concepto_id)->nivel_n + 1;

            $concepto->pr_concepto_id = $request->pr_concepto_id;
        }


        $concepto->pr_nivel_id = PrNiveles::where('nivel',$nivel)->first()->id;
        $concepto->nivel_n = $nivel;

        $concepto->save();

        return (new Resource($concepto))->response()->setStatusCode(201);
    }

    public function update(ConceptoRequest $request, PrConcepto $concepto)
    {
        $concepto->update($request->all());

        return (new Resource($concepto))->response()->setStatusCode(202);

    }


    public function destroy(PrConcepto $concepto)
    {
        try{
            $concepto->delete();
            return response()->json('Eliminado correctamente', 204);
        }catch (\Exception $exception)
        {
            return $exception;
        }
    }
}
