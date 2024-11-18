<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConcumsRequest;
use App\Http\Controllers\Controller;
use App\Models\CuentasMedicas\CmConcum;
use Illuminate\Http\Resources\Json\Resource;

class ConcumsController extends Controller
{
    public function index() {

    }
    public function store(ConcumsRequest $request)
    {
        $concum = CmConcum::create($request->all());
        return new Resource($concum->load('cum','glosa'));
    }

    public function show($cm_visita_id)
    {
        $concum = CmConcum::where('cm_convisita_id',$cm_visita_id)
            ->with('cum','glosa')->get();
        return new Resource($concum);
    }

    public function update(ConcumsRequest $request, $id)
    {
        $concum = CmConcum::find($id);
        $concum->update($request->except('cm_convisita_id','rs_cum_id'));
        return new Resource($concum->load('cum','glosa'));
    }

    public function destroy($id)
    {
        try{
            $concum = CmConcum::find($id);
            $concum->delete();
            if ($concum) return response()->json(['state' => 'delete']);
            else return response()->json(['state' => 'notDelete']);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
