<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConegresoRequest;
use App\Models\CuentasMedicas\CmConcurrencia;
use App\Models\CuentasMedicas\CmConegreso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class ConegresosController extends Controller
{
    public function index() {

    }

    public function show($concurrencia_id){
        $egreso = CmConegreso::where('cm_concurrencia_id',$concurrencia_id)->with(CmConegreso::allRelations())->first();
        return $egreso;
    }

    public function store(ConegresoRequest $request){
        $egreso = CmConegreso::create($request->all());
        $this->change_estatus($request->cm_concurrencia_id,'Cerrada');
        if($request->estado_salida === 'Remitido' && $request->au_referencia_id){
            $egreso->concurrencia->au_referencia_id = $request->au_referencia_id;
            $egreso->concurrencia->save();
        }

        $egreso->load(CmConegreso::allRelations());
        return new Resource($egreso);
    }

    public function update(ConegresoRequest $request, $id){
        $egreso = CmConegreso::find($id);
        $egreso->update($request->all());

        $this->change_estatus($request->cm_concurrencia_id,'Cerrada');

        if($request->estado_salida === 'Remitido' && $request->au_referencia_id){
            $egreso->concurrencia->au_referencia_id = $request->au_referencia_id;
            $egreso->concurrencia->save();
        }

        $egreso->load(CmConegreso::allRelations());
        return new Resource($egreso);
    }

    public function destroy($id)
    {
        try{
            $egreso = CmConegreso::find($id);

            $this->change_estatus($egreso->cm_concurrencia_id,'Abierta');
            $egreso->delete();

            if ($egreso) return response()->json(['state' => 'delete']);
            else return response()->json(['state' => 'notDelete']);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    function change_estatus($concurrencia_id, $estado) {
        $concurrencia = CmConcurrencia::find($concurrencia_id);
        $concurrencia->estado = $estado;
        $concurrencia->save();
    }
}
