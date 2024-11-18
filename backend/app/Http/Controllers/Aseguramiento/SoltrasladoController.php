<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorRespuestas;
use App\Http\Controllers\Controller;
use App\Http\Requests\Aseguramiento\S2R2Request;
use App\Http\Requests\Aseguramiento\SoltrasladoRequest;
use App\Http\Resources\Aseguramiento\SoltrasladosResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsSoltraslado;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\RespuestaDBUA;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class SoltrasladoController extends Controller
{
    public function index()
    {
        $tipo = request()->tipo;

        $max_s2 = RespuestaDBUA::join('as_soltraslados','as_soltraslados.as_svid_id','as_s1vids.id')
            ->whereRaw("substr(as_s1vids.nombreArchivo,1,2) = '{$tipo}'")->max('as_s1vids.id');

        if(Input::get('per_page')){
            return  SoltrasladosResource::collection(
                AsSoltraslado::with('afiliado','eps')
                    ->where('as_svid_id',$max_s2)
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return SoltrasladosResource::collection(
            AsSoltraslado::with('afiliado','eps')
                ->where('as_svid_id',$max_s2)
                ->pimp()
                ->orderBy('updated_at','desc')
                ->get()
        );
    }
    public function show(AsAfiliado $soltraslado)
    {
        $soltraslados = $soltraslado->soltraslados()->with('tramite.gsUsuario')->get();
        return new Resource($soltraslados);
    }

    public function update(SoltrasladoRequest $request, AsSoltraslado $soltraslado)
    {
        $tramite_old = $soltraslado->tramite;

        $soltraslado->as_tramite_id = null;
        $soltraslado->save();
        if($tramite_old){
            $tramite_old->delete();
        }

        $soltraslado->load('archivo');
        $tramite = AsTramite::create([
            'tipo_tramite' => $this->getTipoTramitePorFileName($soltraslado->archivo['nombre']),
            'clase_tramite' => 'Manual',
            'fecha_radicacion' => today()->toDateString(),
            'estado' => 'Radicado',
            'gs_usuradica_id' => Auth::user()->id,
            'gs_usuario_id' => Auth::user()->id
        ]);

        $soltraslado->update($request->all());

        $soltraslado->as_tramite_id = $tramite->id;
        $soltraslado->save();

        $soltraslado->load('afiliado','eps');
        return new SoltrasladosResource($soltraslado);
    }


    public function store(S2R2Request $request)
    {
        try{

            $procesador = new ProcesadorRespuestas($request);

            //$procesador = new $request->tipo($request->s2->path());

            return $procesador->procesarArchivos();
        }catch (\Exception $e)
        {
            return $e;
        }

    }
    private function getTipoTramitePorFileName($fileName)
    {
        $fileName = substr($fileName,0,2);

        if($fileName === 'R2') return 'R4';

        if($fileName === 'S2') return 'S4';

        return $fileName;
    }

}
