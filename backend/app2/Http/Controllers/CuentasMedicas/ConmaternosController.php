<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConmaternosRequest;
use App\Models\CuentasMedicas\CmConmaterno;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class ConmaternosController extends Controller
{
    public function show($concurrencia_id)
    {
        return response()->json(
            CmConmaterno::where('cm_concurrencia_id',$concurrencia_id)->with(CmConmaterno::allRelations())->first()
        );
    }

    public function store(ConmaternosRequest $conmaternosRequest)
    {
        try{
            DB::beginTransaction();

            $materno = CmConmaterno::create($conmaternosRequest->all());
            $this->subirHistoriaClinica($materno, $conmaternosRequest->file('file'));
            $materno->load(CmConmaterno::allRelations());

            DB::commit();
            return new Resource($materno);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(ConmaternosRequest $conmaternosRequest, $id)
    {
        try{
            DB::beginTransaction();

            $materno = CmConmaterno::find($id);
            $materno->update($conmaternosRequest->except('historia_clinica_id'));
            if ($conmaternosRequest->file('file'))
                $this->subirHistoriaClinica($materno, $conmaternosRequest->file('file'));
            $materno->load(CmConmaterno::allRelations());

            DB::commit();
            return new Resource( $materno );
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    private function subirHistoriaClinica(CmConmaterno $conmaterno, $file) {
        if($file) {
            $historia_clinica = ArchivoTrait::subirArchivo($file, 'CuentasMedicas/Concurrencia/Maternos');
            $conmaterno->historia_clinica_id = $historia_clinica->id;
            $conmaterno->save();
        }
    }
}
