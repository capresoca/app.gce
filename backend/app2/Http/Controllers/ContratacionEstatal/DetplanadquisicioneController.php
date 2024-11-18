<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Requests\ContratacionEstatal\DetplanadquisicioneRequest;
use App\Http\Resources\ContratacionEstatal\DetplanadquisicioneResource;
use App\Models\ContratacionEstatal\CeDetplanadquisicione;
use App\Models\ContratacionEstatal\CePlanadquisicione;
use App\Models\Pagos\PgUniapoyo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DetplanadquisicioneController extends Controller
{

    public function index() {}

    public function store(CePlanadquisicione $ce_planadquisicione, DetplanadquisicioneRequest $request)
    {
        $detalle = $ce_planadquisicione->detalles()->create($request->except('rubros'));
        $detalle->rubros()->attach($this->rubrosID($request->rubros));
        return new DetplanadquisicioneResource($detalle);
    }

    public function show(CePlanadquisicione $ce_planadquisicione, CeDetplanadquisicione $detalle) {
        $detadquisione = $detalle->load('bienServicio', 'uniApoyo', 'modContratacione', 'rubros');
        return new DetplanadquisicioneResource($detadquisione);
    }

    public function update(CePlanadquisicione $ce_planadquisicione, DetplanadquisicioneRequest $request, $id) {
        DB::beginTransaction();
        $detalle = CeDetplanadquisicione::find($id);
        $detalle->fill($request->except('rubros'));
        $detalle->save();
        $detalle->rubros()->detach();
        $detalle->rubros()->attach($this->rubrosID($request->rubros));
        $detalle->load('bienServicio', 'uniApoyo', 'modContratacione', 'rubros');
        DB::commit();
        return new DetplanadquisicioneResource($detalle);
    }

    public function destroy(CePlanadquisicione $ce_planadquisicione, CeDetplanadquisicione $detalle)
    {
        try {
            $detalle->rubros()->detach();
            $detalle->delete();

            return response()->json([
                'message' => 'El detallle se ha removido del plan.'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception,
                'message' => $exception->getMessage()
            ], 500);
        }

    }

    public function rubrosID ($requests) {
        $rubros = [];
        foreach ($requests as $res) {
            array_push($rubros ,$res['id']);
        }
        return $rubros;
    }
}
