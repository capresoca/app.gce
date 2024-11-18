<?php

namespace App\Http\Controllers\OficinaJuridica;

use App\Http\Requests\OficinaJuridica\TutimpugnacionRequest;
use App\Http\Resources\OficinaJuridica\TutimpugnacioneResource;
use App\Models\General\GnArchivo;
use App\Models\OficinaJuridica\OjTutela;
use App\Models\OficinaJuridica\OjTutfallo;
use App\Models\OficinaJuridica\OjTutimpugnacione;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class TutimpugnacioneController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return TutimpugnacioneResource::collection(
                OjTutimpugnacione::with( 'archivo', 'tutela', 'tutfallo')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return TutimpugnacioneResource::collection(OjTutimpugnacione::with('archivo', 'tutela', 'tutfallo')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(TutimpugnacionRequest $request)
    {
        $oj_tutimpugnacione = new OjTutimpugnacione();
        $oj_tutimpugnacione->fill($request->except('gn_archivo_id'));
        if ($request->hasFile('archivo')) {
            $archivo_impugnacion = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/impugnacion');
            $oj_tutimpugnacione->gn_archivo_id = $archivo_impugnacion->id;
        }
        $this->estadoTutela($request->oj_tutela_id);
        $oj_tutimpugnacione->save();
        $oj_tutimpugnacione->load('archivo', 'tutela', 'tutfallo');
        return new TutimpugnacioneResource($oj_tutimpugnacione);
    }

    public function show(OjTutimpugnacione $oj_tutimpugnacione)
    {
        return new TutimpugnacioneResource($oj_tutimpugnacione->load('archivo', 'tutela', 'tutfallo'));
    }

    public function update(Request $request, $id){}

    public function destroy($id){}

    public function actualizar (TutimpugnacionRequest $request, $id) {

        $oj_tutimpugnacione = OjTutimpugnacione::find($id);
        $oj_tutimpugnacione->fill($request->except('archivo'));
        if ($request->hasFile('archivo')) {
            Storage::delete($oj_tutimpugnacione->archivo->ruta);
            $archivo = $oj_tutimpugnacione->archivo;
            $archivo_impugnacion = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/impugnacion');
            $oj_tutimpugnacione->gn_archivo_id = $archivo_impugnacion->id;
            $oj_tutimpugnacione->save();
            $archivo->delete();
        }
        $this->estadoTutela($request->oj_tutela_id);
        $oj_tutimpugnacione->save();
        $oj_tutimpugnacione->load('archivo', 'tutela', 'tutfallo');
        return response()->json([
            'message' => 'Se ha actualizado correctamente la impugnación.',
            'data' => new TutimpugnacioneResource($oj_tutimpugnacione)
        ]);
    }

    public function downloadFile ($file) {
        $archivo_impugnacion = GnArchivo::find($file);
        return Storage::download($archivo_impugnacion->ruta, $archivo_impugnacion->nombre);
    }

    public function compleFallo ($id) {
        try {
            $fallos = OjTutfallo::where('oj_tutela_id', $id)->get();
            return response()->json([
                'fallos'=> $fallos
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Error en el servidor.'
            ]);
        }
    }

    public function estadoTutela ($tutela) {
        try {
            $newEstadoTutela = OjTutela::find($tutela);
            $newEstadoTutela->estado = 'Impugnada';
            $newEstadoTutela->save();

            return 'Se ha actualizado el estado de la tutela.';
        } catch (\Exception $exception) {
            return 'Error tutela';
        }
    }
}
