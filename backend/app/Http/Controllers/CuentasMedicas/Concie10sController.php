<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\Concie10sRequest;
use App\Http\Controllers\Controller;
use App\Models\CuentasMedicas\CmConcie10;
use Illuminate\Http\Resources\Json\Resource;

class Concie10sController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Concie10sRequest $request)
    {
        $diagnostico = CmConcie10::create($request->all());
        return new Resource($diagnostico->load('visita','diagnostico_relacionado','glosa'));
    }

    public function show($cm_visita_id)
    {
        $diagnostico = CmConcie10::where('cm_convisita_id',$cm_visita_id)
            ->with('visita','diagnostico_relacionado','glosa')->get();
        return new Resource($diagnostico);
    }

    public function update(Concie10sRequest $request, $id)
    {
        $diagnostico = CmConcie10::find($id);
        $diagnostico->update($request->except('cm_convisita_id'));
        return new Resource($diagnostico->load('visita','diagnostico_relacionado','glosa'));
    }

    public function destroy($id)
    {
        try{
            $dianostico = CmConcie10::find($id);
            $dianostico->delete();
            if ($dianostico) return response()->json(['state' => 'delete']);
            else return response()->json(['state' => 'notDelete']);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
