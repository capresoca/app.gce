<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContratacionEstatal\ProconestprevioRequest;
use App\Http\Resources\ContratacionEstatal\ProconestprevioResource;
use App\Models\ContratacionEstatal\CeProconestprevio;
use App\Models\ContratacionEstatal\CeProconminutageo;
use App\Models\ContratacionEstatal\CeProconminutaupcedade;
use App\Models\ContratacionEstatal\CeProconminutaupcservicio;
use App\Models\ContratacionEstatal\CeProcontractuale;
use App\Models\Presupuesto\PrSolicitudCp;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ProconestprevioController extends Controller
{
//    public function __construct(ProconestprevioRepository $repoProconestprevio) {
//        $this->repoProconestprevio = $repoProconestprevio;
//    }
    public function index()
    {
        if (Input::get('per_page')) {
            $ce_proconestprevios = CeProconestprevio::with([
                'actividades', 'forpagos', 'garantias', 'tipoContratacion', 'solicitudCdp', 'modalidad'
            ])
                ->pimp()->orderBy('updated_at', 'desc')->paginate(Input::get('per_page'));
            return ProconestprevioResource::collection($ce_proconestprevios);
        }

        $ce_proconestprevio = new CeProconestprevio();

        return ProconestprevioResource::collection(CeProconestprevio::with($ce_proconestprevio->allRelations())
            ->pimp()->orderBy('updated_at', 'desc')->get());

//        return ProconestprevioResource::collection(CeProconestprevio::with([
//            'actividades',
//            'forpagos',
//            'garantias',
//            'tipoContratacion',
//            'lugarEjecucion',
//            'rubro',
//            'solicitudCdp',
//            'modalidad'
//        ])
//            ->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(ProconestprevioRequest $request)
    {
        try {
            DB::beginTransaction();

            $estudio_previo = $request->all();
            $estudio_previo['consecutivo'] = CeProcontractuale::find($estudio_previo['ce_procontractuale_id'])->consecutivo;
            $ce_proconestprevio = CeProconestprevio::create($estudio_previo);

            if ($request->imputacion_presupuestal) {
                $ce_proconestprevio->imputacionPresupuestal()->createMany($request->imputacion_presupuestal);
            }

            if ($request->actividades) {
                $ce_proconestprevio->actividades()->createMany($request['actividades']);
            }

            if ($request->garantias) {
                $ce_proconestprevio->garantias()->createMany($request['garantias']);
            }

            if ($request['proconminutageos']) {

                foreach ($request['proconminutageos'] as $key => $ProconminutageoRequest) {

                    $upcserviciosRequest = $ProconminutageoRequest['upcservicios'];
                    $upcedadesRequest = $ProconminutageoRequest['upcedades'];

                    // Crea el Proconminutageo nuevo y le asigna su id a cada upcservicios y upcedades
                    $proconminutageoExistente = $ce_proconestprevio->proconminutageos()->create($ProconminutageoRequest);

                    if ($proconminutageoExistente) {
                        foreach ($upcserviciosRequest as $key2 => $upcservicio) {
                            $upcserviciosRequest[$key2]['ce_proconminutageo_id'] = $proconminutageoExistente->id;
                        }

                        foreach ($upcedadesRequest as $key2 => $upcedad) {
                            $upcedadesRequest[$key2]['ce_proconminutageo_id'] = $proconminutageoExistente->id;
                        }

                        // Crear los Upcservicios y Upcedades nuevos
                        $proconminutageoExistente->upcservicios()->createMany($upcserviciosRequest);
                        $proconminutageoExistente->upcedades()->createMany($upcedadesRequest);
                    }
                }
            }

            if ($request->forpagos) {
                $ce_proconestprevio->forpagos()->createMany($request['forpagos']);
            }

            if ($request->estado === 'Confirmado') {
                $solicitud_cdp = $this->solicitudCdp($ce_proconestprevio);
                $ce_proconestprevio->pr_solicitud_cp_id = $solicitud_cdp->id;
                $ce_proconestprevio->confirmo = Auth::user()->id;
                $ce_proconestprevio->confirmado_at = today()->toDateString();
                $ce_proconestprevio->save();
            }

            $ce_proconestprevio->valor = $ce_proconestprevio->valor_contrato;
            $ce_proconestprevio->save();

            $ce_proconestprevio->load($ce_proconestprevio->allRelations());

            DB::commit();
            return response()->json([
                'message' => 'Se ha creado el estudio previo correctamente.',
                'data' => new ProconestprevioResource($ce_proconestprevio)
            ], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
        }

    }

    public function show(CeProconestprevio $ce_proconestprevio)
    {
        $ce_proconestprevio->load($ce_proconestprevio->allRelations());

        return new ProconestprevioResource($ce_proconestprevio);
    }

    public function update(ProconestprevioRequest $request, $id)
    {
        $request = $request->all();
        $request['consecutivo'] = CeProcontractuale::find($request['ce_procontractuale_id'])->consecutivo;
        $ce_proconestprevio = CeProconestprevio::find($id);

        $estadoActual = $ce_proconestprevio->estado;

        try {
            DB::beginTransaction();
            if ($request['imputacion_presupuestal']) {
                $ce_proconestprevio->imputacionPresupuestal()->delete();
                $ce_proconestprevio->imputacionPresupuestal()->createMany($request['imputacion_presupuestal']);
            } else {
                $ce_proconestprevio->imputacionPresupuestal()->delete();
            }

            if ($request['actividades']) {
                $ce_proconestprevio->actividades()->delete();
                $ce_proconestprevio->actividades()->createMany($request['actividades']);
            } else {
                $ce_proconestprevio->actividades()->delete();
            }

            if ($request['garantias']) {
                $ce_proconestprevio->garantias()->delete();
                $ce_proconestprevio->garantias()->createMany($request['garantias']);
            } else {
                $ce_proconestprevio->garantias()->delete();
            }

            if ($request['forpagos']) {
                $ce_proconestprevio->forpagos()->delete();
                $ce_proconestprevio->forpagos()->createMany($request['forpagos']);
            } else {
                $ce_proconestprevio->forpagos()->delete();
            }

            if ($request['proconminutageos']) {

                foreach ($request['proconminutageos'] as $key => $ProconminutageoRequest) {

                    $upcserviciosRequest = $ProconminutageoRequest['upcservicios'];
                    $upcedadesRequest = $ProconminutageoRequest['upcedades'];

                    // Obtiene el Proconminutageos existente
                    $proconminutageoExistente = CeProconminutageo::find($ProconminutageoRequest['id']);

                    if ($proconminutageoExistente) {
                        // Elimina Upcservicios, Upcedades y Proconminutageos existentes
                        if ($upcserviciosRequest) {
                            $proconminutageoExistente->upcservicios()->delete();
                        }
                        if ($upcedadesRequest) {
                            $proconminutageoExistente->upcedades()->delete();
                        }
                        $proconminutageoExistente->delete();
                    }

                    // Crea el Proconminutageo nuevo y le asigna su id a cada upcservicios y upcedades
                    $proconminutageoExistente = $ce_proconestprevio->proconminutageos()->create($ProconminutageoRequest);

                    foreach ($upcserviciosRequest as $key2 => $upcservicio) {
                        $upcserviciosRequest[$key2]['ce_proconminutageo_id'] = $proconminutageoExistente->id;
                    }

                    foreach ($upcedadesRequest as $key2 => $upcedad) {
                        $upcedadesRequest[$key2]['ce_proconminutageo_id'] = $proconminutageoExistente->id;
                    }

                    // Crear los Upcservicios y Upcedades nuevos
                    $proconminutageoExistente->upcservicios()->createMany($upcserviciosRequest);
                    $proconminutageoExistente->upcedades()->createMany($upcedadesRequest);

                }
            }

            if ($estadoActual != 'Confirmado' && $request['estado'] === 'Confirmado') {
                $solicitud_cdp = $this->solicitudCdp($ce_proconestprevio);
                $ce_proconestprevio->pr_solicitud_cp_id = $solicitud_cdp->id;
                $ce_proconestprevio->confirmo = Auth::user()->id;
                $ce_proconestprevio->confirmado_at = today()->toDateString();
            }

            $ce_proconestprevio->fill($request);
            $ce_proconestprevio->save();

            $ce_proconestprevio->valor = $ce_proconestprevio->valor_contrato;
            $ce_proconestprevio->save();

            $ce_proconestprevio->load($ce_proconestprevio->allRelations());

            DB::commit();
            return response()->json([
                'message' => 'Se actualizó el estudio previo correctamente.',
                'data' => new ProconestprevioResource($ce_proconestprevio)
            ], 201);

        } catch (\Exception $ex) {
            DB::rollBack();
            abort(500, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
    }

    public function crearCopia($request)
    {
        try {
            DB::beginTransaction();
            $ce_proconestprevio = CeProconestprevio::with(
                'imputacionPresupuestal',
                'actividades',
                'garantias',
                'forpagos',
                'proconminutageos',
                'proconminutageos.upcservicios',
                'proconminutageos.upcedades'
            )->create((array)$request);

            if ($request['imputacion_presupuestal']) {
                $ce_proconestprevio->imputacionPresupuestal()->createMany($request['imputacion_presupuestal']);
            }

            if ($request['actividades']) {
                $ce_proconestprevio->actividades()->createMany($request['actividades']);
            }

            if ($request['proconminutageos']) {
                $ce_proconestprevio->proconminutageos()->createMany($request['proconminutageos']);
            }

            if ($request['garantias']) {
                $ce_proconestprevio->garantias()->createMany($request['garantias']);
            }

            if ($request['forpagos']) {
                $ce_proconestprevio->forpagos()->createMany($request['forpagos']);
            }

            DB::commit();
            return response()->json([
                'data' => new ProconestprevioResource($ce_proconestprevio)
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
        }
    }

    /**
     * @param $proconestprevio
     * @return mixed
     * @throws \Exception
     */
    private function solicitudCdp($proconestprevio)
    {
        $objeto_acontratar = $proconestprevio->proceso->objeto;

        if (!$proconestprevio->imputacionPresupuestal->count()) {
            throw new \Exception('No se ha realizado la imputación presupuestal de los estudios previos');
        }

        try {
            DB::beginTransaction();

            $solicitud = PrSolicitudCp::create([
                'fecha' => today()->toDateString(),
                'objeto_acontratar' => $objeto_acontratar,
                'valor' => $proconestprevio->valor,
                'tipo' => 'Contrato',
                'desc_necesidad' => $proconestprevio->desc_necesidad,
                'ce_proconestudioprevio_id' => $proconestprevio->id
            ]);

            foreach ($proconestprevio->imputacionPresupuestal as $rubro) {
                $solicitud->rubros()->create([
                    'pr_detstrgasto_id' => $rubro->pr_detstrgasto_id,
                    'valor' => $rubro->valor
                ]);
            }

            DB::commit();

            return $solicitud;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function usuariosFirmanEstudioPrevio()
    {
        $usuarios_proyectan = User::select('id', 'name', 'email')
            ->where([
                ['tipo', '=', 'Funcionario'],
                ['state', '=', 'Activo'],
                ['rs_entidad_id', '=', null],
            ])->orderBy('name', 'asc')->get();

        $usuarios_revisan = User::select('id', 'name', 'email')
            ->where([
                ['tipo', '=', 'Funcionario'],
                ['state', '=', 'Activo'],
                ['rs_entidad_id', '=', null],
            ])->orderBy('name', 'asc')->get();

        $usuarios_aprueban = User::select('id', 'name', 'email')
            ->where([
                ['tipo', '=', 'Funcionario'],
                ['state', '=', 'Activo'],
                ['rs_entidad_id', '=', null],
            ])->orderBy('name', 'asc')->get();

        return response()->json([
            'proyectan' => $usuarios_proyectan,
            'revisan' => $usuarios_revisan,
            'aprueban' => $usuarios_aprueban,
        ]);
    }
}

