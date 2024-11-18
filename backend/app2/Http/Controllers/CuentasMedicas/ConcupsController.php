<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConcupsRequest;
use App\Models\CuentasMedicas\CmConcup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class ConcupsController extends Controller
{
    public function index() {
        //
    }

     public function store(ConcupsRequest $request)
    {
        $concups = CmConcup::create($request->all());
        return new Resource($concups->load('cup','glosa'));
    }

    public function show($cm_visita_id)
    {
        $concups = CmConcup::where('cm_convisita_id',$cm_visita_id)
            ->with('cup','glosa')->get();
        return new Resource($concups);
    }

    public function update(ConcupsRequest $request, $id)
    {
        $concups = CmConcup::find($id);
        $concups->update($request->except('rs_cup_id','cm_convisita_id'));
        return new Resource($concups->load('cup','glosa'));
    }

    public function destroy($id)
    {
        try{
            $concups = CmConcup::find($id);
            $concups->delete();
            if ($concups) return response()->json(['state' => 'delete']);
            else return response()->json(['state' => 'notDelete']);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
