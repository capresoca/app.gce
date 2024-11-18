<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConaltocostoRequest;
use App\Models\CuentasMedicas\CmConaltocosto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class ConaltocostosController extends Controller
{
    public function index() {

    }

    public function store(ConaltocostoRequest $request) {
        $altoCosto = CmConaltocosto::create($request->all());
        return new Resource($altoCosto->load('diagnostico'));
    }

    public function show($concurrencia_id){
        $altoCosto = CmConaltocosto::where('cm_concurrencia_id',$concurrencia_id)->with('diagnostico')->first();
        return new Resource($altoCosto);
    }

    public function update(ConaltocostoRequest $request, $id){
        $altoCosto = CmConaltocosto::find($id);
        $altoCosto->update($request->except('cm_concurrencia_id'));
        return new Resource($altoCosto->load('diagnostico'));
    }

    public function destroy($id)
    {
        try{
            $altoCosto = CmConaltocosto::find($id);
            $altoCosto->delete();
            if ($altoCosto) return response()->json(['state' => 'delete']);
            else return response()->json(['state' => 'notDelete']);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
