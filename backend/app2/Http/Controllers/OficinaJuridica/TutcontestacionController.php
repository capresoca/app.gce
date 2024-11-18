<?php

namespace App\Http\Controllers\OficinaJuridica;

use App\Http\Requests\OficinaJuridica\TutcontestacionRequest;
use App\Http\Resources\OficinaJuridica\TutcontestacionResource;
use App\Models\General\GnArchivo;
use App\Models\OficinaJuridica\OjTutcontestacione;
use App\Models\OficinaJuridica\OjTutela;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class TutcontestacionController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return TutcontestacionResource::collection(
                OjTutcontestacione::with( 'archivo', 'tutela')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return TutcontestacionResource::collection(OjTutcontestacione::with('archivo', 'tutela')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(TutcontestacionRequest $request)
    {
        $oj_tutcontestacione = new OjTutcontestacione();
        $oj_tutcontestacione->fill($request->except('gn_archivo_id'));
        if ($request->hasFile('archivo')) {
            $archivo_contestacion = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/contestacion');
            $oj_tutcontestacione->gn_archivo_id = $archivo_contestacion->id;
        }
        $this->estadoTutela($request->oj_tutela_id);
        $oj_tutcontestacione->save();
        $oj_tutcontestacione->load('archivo', 'tutela');
        return new TutcontestacionResource($oj_tutcontestacione);
    }

    public function show(OjTutcontestacione $oj_tutcontestacione)
    {
        return new TutcontestacionResource($oj_tutcontestacione->load('archivo', 'tutela'));
    }

    public function update(Request $request, $id){}

    public function destroy($id){}

    public function actualizar (TutcontestacionRequest $request, $id) {
        $oj_tutcontestacione = OjTutcontestacione::find($id);
        $oj_tutcontestacione->fill($request->except('archivo'));
        if ($request->hasFile('archivo')) {
            Storage::delete($oj_tutcontestacione->archivo->ruta);
            $archivo = $oj_tutcontestacione->archivo;
            $archivo_contestacion = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/contestacion');;
            $oj_tutcontestacione->gn_archivo_id = $archivo_contestacion->id;
            $oj_tutcontestacione->save();
            $archivo->delete();
        }
        $this->estadoTutela($request->oj_tutela_id);
        $oj_tutcontestacione->save();
        $oj_tutcontestacione->load('archivo', 'tutela');
        return response()->json([
            'message' => 'Se ha actualizado correctamente la tutela contestación.',
            'data' => new TutcontestacionResource($oj_tutcontestacione)
        ]);
    }

    public function downloadFile ($file) {
        $archivo_contestacion = GnArchivo::find($file);
        return Storage::download($archivo_contestacion->ruta, $archivo_contestacion->nombre);
    }

    public function estadoTutela ($tutela) {
        try {
            $newEstadoTutela = OjTutela::find($tutela);
            $newEstadoTutela->estado = 'Contestada';
            $newEstadoTutela->save();

            return 'Se ha actualizado el estado de la tutela.';
        } catch (\Exception $exception) {
            return 'Error tutela';
        }
    }
}
