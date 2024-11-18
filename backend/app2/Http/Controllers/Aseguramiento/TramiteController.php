<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Requests\Aseguramiento\EditTramiteRequest;
use App\Http\Resources\Aseguramiento\AportantesPendientesResource;
use App\Http\Resources\Aseguramiento\AsAfitramitesResource;
use App\Http\Resources\Aseguramiento\AsTramiteResource;
use App\Http\Resources\Aseguramiento\AsTramitesResource;
use App\Http\Resources\Aseguramiento\NovedadesPendientesResource;
use App\Http\Resources\Aseguramiento\TrasladosSubsidiadoResource;
use App\Http\Resources\Aseguramiento\TramiteSolTrasladoResource;
use App\Models\Aseguramiento\AsAfitramite;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTramite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;

class TramiteController extends Controller
{

    public function index(){
        if(Input::get('per_page')){
            return AsTramitesResource::collection(
                AsTramite::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return AsTramiteResource::collection(AsTramite::with('novedad','afiliacion')->pimp()->orderBy('updated_at','desc')->get());

    }

    public function search($per_page = 15, $search = '')
    {
        $first = AsAfitramite::select('id as id_tramite','as_afiliado_id');

        $tramites = AsNovtramite::select('id as id_tramite','as_afiliado_id' )
            ->union($first)->paginate(15);

        return $tramites;

    }

    public function update(EditTramiteRequest $request, AsTramite $tramite){
        $tramite->update($request->all());

        if($request->estado === 'Anulado'){
            $tramite->fecha_anulacion = today()->toDateString();
            $tramite->gs_usuanula_id = Auth::user()->id;
            $tramite->save();
        }

        return response()->json()->setStatusCode(202);
    }

    public function getPendientes()
    {
        $pendientes = collect();


        if(Input::get('afiliaciones')){
            $pendientes->put(
                'afiliaciones',
                AsAfitramitesResource::collection(
                    Astramite::PendientesPorTipo('Afiliacion')
                        ->with('afiliacion.afiliado.municipio')->get()
                )
            );
        }

        if(Input::get('novedades_subsidiado')){
            $pendientes->put(
                'novedades_subsidiado',
                NovedadesPendientesResource::collection(
                    Astramite::PendientesPorTipo(
                        'Novedad Subsidiado')
                        ->with('novedad.afiliado')->get()

                )
            );
        }

        if(Input::get('novedades_contributivo')){
            $pendientes->put(
                'novedades_contributivo',
                NovedadesPendientesResource::collection(
                    Astramite::PendientesPorTipo(
                        'Novedad Contributivo')
                        ->with('novedad.afiliado')->get()

                )
            );
        }
        if(Input::get('afiliaciones_aportante')){
            $pendientes->put(
                'afiliaciones_aportante',
                AportantesPendientesResource::collection(
                    Astramite::PendientesPorTipo(
                        'Afiliacion Aportante')
                        ->with('afiliacionPagador')->get()
                )
            );
        }

        if(Input::get('S4')){
            $pendientes->put(
                'S4',
                TramiteSolTrasladoResource::collection(
                    Astramite::PendientesPorTipo(
                        'S4')->with('solicitudTraslado')->get()
                )
            );
        }

        if(Input::get('R4')){
            $pendientes->put(
                'R4',
                TramiteSolTrasladoResource::collection(
                    Astramite::PendientesPorTipo(
                        'R4')->with('solicitudTraslado')->get()
                )
            );
        }

        if(Input::get('MC')){
            $pendientes->put(
                'MC',
                AsAfitramitesResource::collection(
                    Astramite::PendientesPorTipo('MC')
                        ->with('afiliacion.afiliado.municipio')->get()
                )
            );
        }


        return $pendientes;

    }

    public function getTrasladosPendientes()
    {
        $pendientes = collect();
        if(Input::get('traslado_subsidiado')){
            $pendientes->put(
                'traslados_subsidiado',
                TrasladosSubsidiadoResource::collection(
                    Astramite::TrasladoPorTipo(
                        'Traslado Subsidiado')
                        ->with('traslado.afiliado')->get()
                )
            );
        }

        if(Input::get('traslado_contributivo')){
            $pendientes->put(
                'traslados_contributivo',
                TrasladosSubsidiadoResource::collection(
                    Astramite::TrasladoPorTipo(
                        'Traslado Contributivo')
                        ->with('traslado.afiliado')->get()
                )
            );
        }

        return $pendientes;
    }



}
