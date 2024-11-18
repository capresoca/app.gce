<?php

namespace App\Http\Controllers\OficinaJuridica;

use App\Http\Requests\OficinaJuridica\TutbitacoraRequest;
use App\Http\Resources\OficinaJuridica\TutbitacoraResource;
use App\Models\General\GnArchivo;
use App\Models\OficinaJuridica\OjTutbitacora;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class TutbitacoraController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return TutbitacoraResource::collection(
                OjTutbitacora::with( 'archivo', 'tutela')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return TutbitacoraResource::collection(OjTutbitacora::with('archivo', 'tutela')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(TutbitacoraRequest $request)
    {
        $oj_tutbitacora = new OjTutbitacora();
        $oj_tutbitacora->fill($request->except('gn_archivo_id'));
        if ($request->hasFile('archivo')) {
            $archivo_bitacora = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/bitacora');;
            $oj_tutbitacora->gn_archivo_id = $archivo_bitacora->id;
        }
        $oj_tutbitacora->save();
        $oj_tutbitacora->load('archivo', 'tutela');
        return new TutbitacoraResource($oj_tutbitacora);
    }

    public function show(OjTutbitacora $oj_tutbitacora)
    {
        return new TutbitacoraResource($oj_tutbitacora->load('archivo', 'tutela'));
    }

    public function update(Request $request, $id){}

    public function destroy($id){}

    public function actualizar (TutbitacoraRequest $request, $id) {
        $oj_tutbitacora = OjTutbitacora::find($id);
        $oj_tutbitacora->fill($request->except('archivo'));
        if ($request->hasFile('archivo')) {
            Storage::delete($oj_tutbitacora->archivo->ruta);
            $archivo = $oj_tutbitacora->archivo;
            $archivo_bitacora = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/bitacora');;
            $oj_tutbitacora->gn_archivo_id = $archivo_bitacora->id;
            $oj_tutbitacora->save();
            $archivo->delete();
        }
        $oj_tutbitacora->save();
        $oj_tutbitacora->load('archivo', 'tutela');
        return response()->json([
            'message' => 'Se ha actualizado correctamente la bitacora.',
            'data' => new TutbitacoraResource($oj_tutbitacora)
        ]);
    }

    public function downloadFile ($file) {
        $archivo_bitacora = GnArchivo::find($file);
        return Storage::download($archivo_bitacora->ruta, $archivo_bitacora->nombre);
    }
}
