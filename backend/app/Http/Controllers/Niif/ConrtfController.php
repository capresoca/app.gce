<?php

namespace App\Http\Controllers\Niif;

use App\Http\Requests\Niif\ConrtfRequest;
use App\Http\Resources\Niif\ConrtfResource;
use App\Models\Niif\NfConrtf;
use App\Models\Niif\NfConrtfdetalle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ConrtfController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return ConrtfResource::collection(
                NfConrtf::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ConrtfResource::collection(NfConrtf::pimp()->orderBy('updated_at','desc')->limit(1000)->get());        
    }


    public function store(ConrtfRequest $request)
    {
        try{
            DB::beginTransaction();
                if(!$request->id)
                {
                    $conrtf = NfConrtf::create($request->except('detalles'));
                    $conrtf->detalles()->createMany($request->detalles);
                }else
                {
                    $conrtf = NfConrtf::findOrFail($request->id);
                    $conrtf->update($request->except('detalles'));
                    $conrtf->detalles()->delete();
                    $conrtf->detalles()->createMany($request->detalles);
                }

            DB::commit();
            return new ConrtfResource($conrtf);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Error en el servidor',
                'errors' => $e->getMessage()
            ], 500);
        }
    }


    public function show(NfConrtf $conrtf)
    {
        return new ConrtfResource($conrtf);
    }


    public function removeDetalle(NfConrtfdetalle $detalle)
    {
        try{
            $detalle->delete();
            return response()->json(
                [
                    'message' => 'El detalle se ha eliminado exitosamente'
                ], 200
            );
        }catch (\Exception $e){
            return $e;
        }
    }

}
