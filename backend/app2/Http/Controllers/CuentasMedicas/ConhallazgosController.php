<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConhallazgosRequest;
use App\Models\CuentasMedicas\CmConhallazgo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class ConhallazgosController extends Controller
{
    public function index()
    {
        //
    }

    public function store(ConhallazgosRequest $request)
    {
        $hallazgo = CmConhallazgo::create($request->all());
        return new Resource($hallazgo);
    }

    public function show($cm_visita_id)
    {
        $hallazgo = CmConhallazgo::where('cm_convisita_id',$cm_visita_id)->get();
        return new Resource($hallazgo);
    }

    public function update(ConhallazgosRequest $request, $id)
    {
        $hallazgo = CmConhallazgo::find($id);
        $hallazgo->update($request->except('cm_convisita_id'));
        return new Resource($hallazgo);
    }

    public function destroy($id)
    {
        try{
            $hallazgo = CmConhallazgo::find($id);
            $hallazgo->delete();
            if ($hallazgo) return response()->json(['state' => 'delete']);
            else return response()->json(['state' => 'notDelete']);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
