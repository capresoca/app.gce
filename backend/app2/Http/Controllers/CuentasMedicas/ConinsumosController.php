<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConinsumosRequest;
use App\Http\Controllers\Controller;
use App\Models\CuentasMedicas\CmConinsumo;
use Illuminate\Http\Resources\Json\Resource;

class ConinsumosController extends Controller
{
    public function index()
    {
        //
    }

    public function store(ConinsumosRequest $request)
    {
        $insumo = CmConinsumo::create($request->all());
        return new Resource($insumo);
    }

    public function show($cm_visita_id)
    {
        $insumo = CmConinsumo::where('cm_convisita_id',$cm_visita_id)->get();
        return new Resource($insumo);
    }

    public function update(ConinsumosRequest $request, $id)
    {
        $insumo = CmConinsumo::find($id);
        $insumo->update($request->except('cm_convisita_id'));
        return new Resource($insumo);
    }

    public function destroy($id)
    {
        try{
            $insumo = CmConinsumo::find($id);
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
