<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\CongestionriesgoRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CuentasMedicas\GestionriesgoResource;
use App\Models\CuentasMedicas\CmCongestionriesgo;
use Illuminate\Http\Resources\Json\Resource;

class CongestionriesgoController extends Controller
{
    public function index()
    {
        //
    }

    public function store(CongestionriesgoRequest $request)
    {
        $gestion_riesgo = CmCongestionriesgo::create($request->all());
        return new Resource($gestion_riesgo->load(CmCongestionriesgo::allRelations()));
    }

    public function show($concurrencia_id)
    {
        $gestiones_riesgos = CmCongestionriesgo::where('cm_concurrencia_id',$concurrencia_id)->get();
        return new Resource($gestiones_riesgos->load(CmCongestionriesgo::allRelations()));
    }

    public function update(CongestionriesgoRequest $request, $id)
    {
        $gestion_riesgo = CmCongestionriesgo::find($id);
        $gestion_riesgo->update($request->except('cm_concurrencia_id'));
        return new Resource($gestion_riesgo->load(CmCongestionriesgo::allRelations()));
    }

    public function destroy($id)
    {
        try{
            $insumo = CmCongestionriesgo::find($id);
            $insumo->delete();
            if ($insumo) return response()->json(['state' => 'delete']);
            else return response()->json(['state' => 'notDelete']);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
