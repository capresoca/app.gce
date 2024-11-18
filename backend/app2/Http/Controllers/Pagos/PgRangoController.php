<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Resources\Pagos\PgRangoResource;
use App\Models\Pagos\PgConfiguracione;
use App\Models\Pagos\PgRango;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PgRangoController extends Controller
{

    public function index()
    {
        //
    }


    public function store(PgConfiguracione $pg_configuracione, Request $request)
    {
        $rango = $pg_configuracione->rangos()->create($request->all());

        return new PgRangoResource($rango);
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {

    }


    public function destroy(PgConfiguracione $pg_configuracione, PgRango $rango)
    {
        try{
            $rango->delete();
            return response()->json(
                [
                    'message' => 'El rango se ha removido del tramite'
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
