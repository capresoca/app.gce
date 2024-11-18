<?php

namespace App\Http\Controllers\CuentasMedicas;


use App\Http\Controllers\Controller;
use App\Http\Requests\CuentasMedicas\EventoRequest;
use App\Models\CuentasMedicas\CmConevento;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class EventosController extends Controller
{
     public function store(EventoRequest $eventoRequest)
    {
        try {
            DB::beginTransaction();
            $evento = CmConevento::create($eventoRequest->all());
            $this->subirHistoriaClinica($evento, $eventoRequest->file('file'));
            DB::commit();
            return new Resource($evento->load(CmConevento::allRelations()));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($concurrencia_id)
    {
        $diagnostico = CmConevento::where('cm_concurrencia_id',$concurrencia_id)->with(CmConevento::allRelations())->get();
        return new Resource($diagnostico);
    }

    public function update(EventoRequest $eventoRequest, $id)
    {
        try {
            DB::beginTransaction();
            $evento = CmConevento::find($id);
            $evento->update($eventoRequest->except('cm_convisita_id','historia_clinica_id'));
            if ($eventoRequest->file('file'))
                $this->subirHistoriaClinica($evento, $eventoRequest->file('file'));
            DB::commit();
            return new Resource($evento->load(CmConevento::allRelations()));
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }

    }

    public function destroy($id) {
        try{
            $evento = CmConevento::find($id);
            $evento->delete();
            if ($evento) return response()->json(['state' => 'delete']);
            else return response()->json(['state' => 'notDelete']);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    private function subirHistoriaClinica(CmConevento $conevento, $file) {
        if($file) {
            $evento = ArchivoTrait::subirArchivo($file, 'CuentasMedicas/Concurrencia/Eventos');
            $conevento->historia_clinica_id = $evento->id;
            $conevento->save();
        }
    }
}
