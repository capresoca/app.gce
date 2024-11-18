<?php

namespace App\Http\Controllers\OficinaJuridica;

use App\Http\Requests\OficinaJuridica\TutdesacatoRequest;
use App\Http\Resources\OficinaJuridica\TutdesacatoResource;
use App\Models\General\GnArchivo;
use App\Models\OficinaJuridica\OjTutdesacato;
use App\Models\OficinaJuridica\OjTutela;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class TutdesacatoController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return TutdesacatoResource::collection(
                OjTutdesacato::with( 'archivo', 'tutela')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return TutdesacatoResource::collection(OjTutdesacato::with('archivo', 'tutela')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(TutdesacatoRequest $request)
    {
        $oj_tutdesacato = new OjTutdesacato();
        $oj_tutdesacato->fill($request->except('gn_archivo_id'));
        if ($request->hasFile('archivo')) {
            $archivo_desacato = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/desacato');
            $oj_tutdesacato->gn_archivo_id = $archivo_desacato->id;
        }
        $this->estadoTutela($request->oj_tutela_id);
        $oj_tutdesacato->save();
        $oj_tutdesacato->load('archivo', 'tutela');
        return new TutdesacatoResource($oj_tutdesacato);
    }

    public function show(OjTutdesacato $oj_tutdesacato)
    {
        return new TutdesacatoResource($oj_tutdesacato->load('archivo', 'tutela'));
    }

    public function update(Request $request, $id){}

    public function destroy($id){}

    public function actualizar (TutdesacatoRequest $request, $id) {

        $oj_tutdesacato = OjTutdesacato::find($id);
        $oj_tutdesacato->fill($request->except('archivo'));
        if ($request->hasFile('archivo')) {
            Storage::delete($oj_tutdesacato->archivo->ruta);
            $archivo = $oj_tutdesacato->archivo;
            $archivo_desacato = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/desacato');;
            $oj_tutdesacato->gn_archivo_id = $archivo_desacato->id;
            $oj_tutdesacato->save();
            $archivo->delete();
        }
        $this->estadoTutela($request->oj_tutela_id);
        $oj_tutdesacato->save();
        $oj_tutdesacato->load('archivo', 'tutela');
        return response()->json([
            'message' => 'Se ha actualizado correctamente el desacato.',
            'data' => new TutdesacatoResource($oj_tutdesacato)
        ]);
    }

    public function downloadFile ($file) {
        $archivo_desacato = GnArchivo::find($file);
        return Storage::download($archivo_desacato->ruta, $archivo_desacato->nombre);
    }

    public function estadoTutela ($tutela) {
        try {
            $newEstadoTutela = OjTutela::find($tutela);
            $newEstadoTutela->estado = 'Desacato';
            $newEstadoTutela->save();

            return 'Se ha actualizado el estado de la tutela.';
        } catch (\Exception $exception) {
            return 'Error tutela';
        }
    }
}
