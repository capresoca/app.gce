<?php

namespace App\Http\Controllers\Niif;

use App\Http\Requests\Niif\AnedeclaraRequest;
use App\Http\Resources\Niif\AnedeclaracionesResource;
use App\Models\Niif\NfAnedeclaracione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnedeclaracionController extends Controller
{
    public function index()
    {
        return AnedeclaracionesResource::collection(NfAnedeclaracione::all());
    }


    public function show(NfAnedeclaracione $anedeclaracione){
        return new AnedeclaracionesResource($anedeclaracione);
    }

    public function store(AnedeclaraRequest $anedeclara){
        $anedeclara = NfAnedeclaracione::create($anedeclara->all());
        return new AnedeclaracionesResource($anedeclara);
    }

    public function update(AnedeclaraRequest $anedeclaraReq, NfAnedeclaracione $anedeclaracione){
        $anedeclaracione->update($anedeclaraReq->all());
        return new AnedeclaracionesResource($anedeclaracione);
    }

    public function destroy(NfAnedeclaracione $anedeclaracione){
        try{
            if(!$anedeclaracione->niifs->count()){
                $anedeclaracione->delete();
                return response()->json(
                    [
                        'message' => 'El anexo se ha eliminado exitosamente'
                    ], 200
                );
            }
            return response()->json(
                [
                    'message' => 'El anexo tiene cuentas asociadas'
                ], 405
            );
        }catch (\Exception $e){
            return $e;
        }
    }
}
