<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\VisitaRequest;
use App\Models\CuentasMedicas\CmConcausalhospitalizacion;
use App\Models\CuentasMedicas\CmConcondclinica;
use App\Models\CuentasMedicas\CmConvisita;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class VisitasController extends Controller
{
    public function index() {

    }
    public function show($cm_concurrencia_id)
    {
        return new Resource(CmConvisita::with(CmConvisita::allRelations())
            ->where('cm_concurrencia_id',$cm_concurrencia_id)
            ->orderBy('created_at','desc')
            ->get());
    }

    public function store(VisitaRequest $visitaRequest)
    {
        try {
            DB::beginTransaction();
            $visita = CmConvisita::create($visitaRequest->all());

            if (isset($visitaRequest->condiciones_clinicas) && !empty($visitaRequest->condiciones_clinicas)) {
                foreach ($visitaRequest->condiciones_clinicas as $key => $value) {
                    $visita->condclinicas()->attach($value['id']);
                }
            }
            if (isset($visitaRequest->cm_causalhospitalizacion_id) && !empty($visitaRequest->cm_causalhospitalizacion_id)) {
                $visitaRequest['cm_convisita_id'] = $visita->id;
                CmConcausalhospitalizacion::create($visitaRequest->all());
            }
            DB::commit();
            return new Resource($visita->load(CmConvisita::allRelations()));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(VisitaRequest $visitaRequest, $id)
    {
        try {
            DB::beginTransaction();
            $visita = CmConvisita::find($id);
            $visita->update($visitaRequest->except('cm_concurrencia_id'));
            if (isset($visitaRequest->condclinicas) && !empty($visitaRequest->condclinicas)) {
                $visita->condclinicas()->detach();
                foreach ($visitaRequest->condclinicas as $key => $value) {
                    $visita->condclinicas()->attach($value['id']);
                }
            }
            if (isset($visitaRequest->cm_causalhospitalizacion_id) && !empty($visitaRequest->cm_causalhospitalizacion_id)) {
                $causa_hospitalizacion = CmConcausalhospitalizacion::where('cm_convisita_id', $visita->id)
                    ->where('cm_causalhospitalizacion_id',$visitaRequest->cm_causalhospitalizacion_id)->first();
                $causa_hospitalizacion->update($visitaRequest->except('cm_convisita_id'));
            }
            DB::commit();
            return new Resource($visita->load(CmConvisita::allRelations()));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
