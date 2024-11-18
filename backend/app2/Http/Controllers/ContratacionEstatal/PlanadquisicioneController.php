<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Requests\ContratacionEstatal\PlanadquisicioneRequest;
use App\Http\Resources\ContratacionEstatal\PlanadquisicioneResource;
use App\Http\Resources\ContratacionEstatal\PlanadquisicionesResource;
use App\Models\ContratacionEstatal\CePlanadquisicione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class PlanadquisicioneController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            $ce_planadquisiciones = CePlanadquisicione::with('detalles')->pimp()->orderBy('updated_at', 'desc')->paginate(Input::get('per_page'));
            return PlanadquisicionesResource::collection($ce_planadquisiciones);
        }
        return PlanadquisicionesResource::collection(CePlanadquisicione::with('detalles')->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(PlanadquisicioneRequest $request)
    {
        $ce_planadquisicione = CePlanadquisicione::create($request->all());
        $ce_planadquisicione->load('detalles');
        return new PlanadquisicionesResource($ce_planadquisicione);
    }

    public function show(CePlanadquisicione $ce_planadquisicione)
    {
        return new PlanadquisicioneResource($ce_planadquisicione->load('detalles', 'detalles.bienServicio', 'detalles.uniApoyo', 'detalles.modContratacione', 'detalles.rubros'));
    }

    public function update(PlanadquisicioneRequest $request, CePlanadquisicione $ce_planadquisicione)
    {
        $ce_planadquisicione->update($request->all());
        $ce_planadquisicione->load('detalles');
        return new PlanadquisicionesResource($ce_planadquisicione);
    }

    public function ByUniapoyo($uniapoyo_id){
        $planes = CePlanadquisicione::whereHas('detalles', function ($query) use($uniapoyo_id){
           $query->where('pg_uniapoyo_id', $uniapoyo_id);
        })->with(['detalles' => function ($query) use ($uniapoyo_id) {
            $query->where('pg_uniapoyo_id', $uniapoyo_id)->with('bienServicio','uniApoyo', 'modContratacione', 'rubros');
        }])->get();

        return PlanadquisicioneResource::collection($planes);
    }
}
