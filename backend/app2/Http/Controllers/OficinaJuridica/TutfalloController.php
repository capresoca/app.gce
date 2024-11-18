<?php

namespace App\Http\Controllers\OficinaJuridica;

use App\Http\Requests\OficinaJuridica\TutfalloRequest;
use App\Http\Resources\OficinaJuridica\TutfalloResource;
use App\Models\General\GnArchivo;
use App\Models\OficinaJuridica\OjTutela;
use App\Models\OficinaJuridica\OjTutfallo;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class TutfalloController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return TutfalloResource::collection(
                OjTutfallo::with( 'archivo', 'tutela', 'juzgado')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return TutfalloResource::collection(OjTutfallo::with('archivo', 'tutela', 'juzgado')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(TutfalloRequest $request)
    {
        $oj_tutfallo = new OjTutfallo();
        $oj_tutfallo->fill($request->except('gn_archivo_id'));
        if ($request->hasFile('archivo')) {
            $archivo_fallo = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/fallo');
            $oj_tutfallo->gn_archivo_id = $archivo_fallo->id;
        }
        $this->estadoTutela($request->oj_tutela_id);
        $oj_tutfallo->save();
        $oj_tutfallo->load('archivo', 'tutela', 'juzgado');
        return new TutfalloResource($oj_tutfallo);
    }

    public function show(OjTutfallo $oj_tutfallo)
    {
        return new TutfalloResource($oj_tutfallo->load('archivo', 'tutela', 'juzgado'));
    }

    public function update(Request $request, $id){}

    public function destroy($id){}

    public function actualizar (TutfalloRequest $request, $id) {
        $oj_tutfallo = OjTutfallo::find($id);
        $oj_tutfallo->fill($request->except('archivo'));
        if ($request->hasFile('archivo')) {
            Storage::delete($oj_tutfallo->archivo->ruta);
            $archivo = $oj_tutfallo->archivo;
            $archivo_fallo = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/fallo');
            $oj_tutfallo->gn_archivo_id = $archivo_fallo->id;
            $oj_tutfallo->save();
            $archivo->delete();
        }
        $this->estadoTutela($request->oj_tutela_id);
        $oj_tutfallo->save();
        $oj_tutfallo->load('archivo', 'tutela', 'juzgado');
        return response()->json([
            'message' => 'Se ha actualizado correctamente el fallo.',
            'data' => new TutfalloResource($oj_tutfallo)
        ]);
    }

    public function downloadFile ($file) {
        $archivo_fallo = GnArchivo::find($file);
        return Storage::download($archivo_fallo->ruta, $archivo_fallo->nombre);
    }

    public function estadoTutela ($tutela) {
        try {
            $newEstadoTutela = OjTutela::find($tutela);
            $newEstadoTutela->estado = 'Fallo';
            $newEstadoTutela->save();

            return 'Se ha actualizado el estado de la tutela.';
        } catch (\Exception $exception) {
            return 'Error tutela';
        }
    }
}
