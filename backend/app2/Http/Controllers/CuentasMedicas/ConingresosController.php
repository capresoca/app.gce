<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConingresoRequest;
use App\Models\CuentasMedicas\CmConingreso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class ConingresosController extends Controller
{
    public function show($id){
        $ingreso = CmConingreso::where('cm_concurrencia_id',$id)->with('entidad_origen', 'diagnostico_ingreso', 'diagnostico_relacionado', 'diagnostico_relacionado2')->first();
        return $ingreso;
    }

    public function store(ConingresoRequest $request){
        $ingreso = CmConingreso::create($request->all());
        return $ingreso->load('entidad_origen', 'diagnostico_ingreso', 'diagnostico_relacionado', 'diagnostico_relacionado2');
    }

    public function update(ConingresoRequest $request, $id){
        $ingreso = CmConingreso::find($id);
        $ingreso->update($request->except('cm_concurrencia_id'));
        return $ingreso->load('entidad_origen', 'diagnostico_ingreso', 'diagnostico_relacionado', 'diagnostico_relacionado2');
    }
}
