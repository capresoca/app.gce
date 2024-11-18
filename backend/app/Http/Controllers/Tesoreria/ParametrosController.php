<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Requests\Tesoreria\ParametrosRequest;
use App\Http\Resources\Tesoreria\ParametrosResource;
use App\Models\Tesoreria\TsParametro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParametrosController extends Controller
{

    public function index()
    {
        $configuracion = TsParametro::with('cajas', 'tipocaja', 'tipoegreso', 'tipoconsignacion', 'tiponotas', 'tipoinversiones', 'tipoaprovecha', 'niifinversiones', 'niifinversionesing', 'niifcxp', 'niifgasto', 'niifdebito', 'niifcredito')->first();

        if($configuracion){
            return response()->json([
                'data' => new ParametrosResource($configuracion),
                'exists' => true,
            ]);
        } else {
            return response()->json([
                'exists' => false,
                'message' => 'Aun no se ha realizado la configuración de los parametros de tesorería.'
            ]);
        }
    }


    /**
     * @param ParametrosRequest $request
     * @return ParametrosResource
     */
    public function store(ParametrosRequest $request)
    {
        $concepto = TsParametro::create($request->all());
        return response()->json([
                'message' => 'El concepto fue creado con exito',
                'data' => TsParametro::where('id',$concepto->id)->with('cajas', 'tipocaja', 'tipoegreso', 'tipoconsignacion', 'tiponotas', 'tipoinversiones', 'tipoaprovecha', 'niifinversiones', 'niifinversionesing', 'niifcxp', 'niifgasto', 'niifdebito', 'niifcredito')->first()
            ]);
    }


    /**
     * @param TsParametro $concepto
     * @return ParametrosResource
     */
    public function show(TsParametro $ts_concepto)
    {
        return new ParametrosResource($ts_concepto);
    }


    public function update(ParametrosRequest $request, $id)
    {
        try {
            $parametro = TsParametro::find($id);
            $parametro->update($request->all());
            $parametro=TsParametro::where('id',$id)->with('cajas', 'tipocaja', 'tipoegreso', 'tipoconsignacion', 'tiponotas', 'tipoinversiones', 'tipoaprovecha', 'niifinversiones', 'niifinversionesing', 'niifcxp', 'niifgasto', 'niifdebito', 'niifcredito')->first();
            return new ParametrosResource($parametro);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getFirst()
    {
        try {
            $parametro=TsParametro::with('cajas', 'tipocaja', 'tipoegreso', 'tipoconsignacion', 'tiponotas', 'tipoinversiones', 'tipoaprovecha', 'niifinversiones', 'niifinversionesing', 'niifcxp', 'niifgasto', 'niifdebito', 'niifcredito')->orderBy('id', 'ASC')->first();
            if($parametro){
                 return response()->json([
                    'exists' => true,
                    'message'=> 'Configuracion registrada',
                    'data' => $parametro
                ],200);
            }
            return response()->json([
                'exists' => false,
                'message' => 'El concepto no existe'
            ],204);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
