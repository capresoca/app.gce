<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContratacionEstatal\ProcontractualeRequest;
use App\Http\Resources\ContratacionEstatal\ProcontractualeResource;
use App\Http\Resources\ContratacionEstatal\ProcontractualesResource;
use App\Models\ContratacionEstatal\CeProcontractuale;
use App\Models\TalentoHumano\ThEncargo;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class ProcontractualeController extends Controller
{
    public function index()
    {
        if (Input::get('sort') === null) {
            return ProcontractualesResource::collection(
                CeProcontractuale::with('dependencia', 'estudiosPrevios')->pimp()
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        if (Input::get('per_page')) {
            return ProcontractualesResource::collection(
                CeProcontractuale::with('dependencia', 'estudiosPrevios')->pimp()
                    ->paginate(Input::get('per_page'))
            );
        }
        return ProcontractualeResource::collection(CeProcontractuale::with('dependencia', 'estudiosPrevios')->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(ProcontractualeRequest $request)
    {
        $ce_procontractuale = CeProcontractuale::create($request->all());
        $data = $request->estudios_previos;
        if (isset($data)) {
            $imputaciones = [];
            $actividades = [];
            $garantias = [];
            $forpagos = [];

            if ($data['imputacion_presupuestal'] !== []) {
                $imputaciones = $this->copiaRelaEstudioPrevio($data['imputacion_presupuestal'], 'ce_estprevio_id');
            }

            if ($data['actividades'] !== []) {
                $actividades = $this->copiaRelaEstudioPrevio($data['actividades'], 'ce_proconestprevio_id');
            }

            if ($data['garantias'] !== []) {
                $garantias = $this->copiaRelaEstudioPrevio($data['garantias'], 'ce_proconestprevio_id');
            }

            if ($data['forpagos'] !== []) {
                $forpagos = $this->copiaRelaEstudioPrevio($data['forpagos'], 'ce_proconestprevio_id');
            }

            $proconestprevio = $this->getArrEstudioPrevio($ce_procontractuale, $data, $imputaciones, $actividades, $garantias, $forpagos);
            (new ProconestprevioController())->crearCopia($proconestprevio);
        }

        $ce_procontractuale->load($ce_procontractuale->allRelations());

        return response()->json([
            'message' => 'Se ha creado ' . (!isset($data) ? 'el ' : 'la copia del ') . 'proceso cóntractual correctamente',
            'procontractuale' => new ProcontractualesResource($ce_procontractuale),
            'procontractuale_o' => new ProcontractualeResource($ce_procontractuale)
        ], 200);
    }


    public function show(CeProcontractuale $ce_procontractuale)
    {
        $procontractuale = $ce_procontractuale->load($ce_procontractuale->allRelations());
        return new ProcontractualeResource($procontractuale);
    }


    public function update(ProcontractualeRequest $request, CeProcontractuale $ce_procontractuale)
    {
        $ce_procontractuale->update($request->all());
        $ce_procontractuale->load($ce_procontractuale->allRelations());
        return response()->json([
            'message' => 'Se ha editado el proceso cóntractual correctamente',
            'procontractuale' => new ProcontractualesResource($ce_procontractuale),
            'procontractuale_o' => new ProcontractualeResource($ce_procontractuale)
        ], 201);
//        return new ProcontractualeResource($ce_procontractuale);
    }

    public function getPdfEstudioPrevio()
    {
        try {
            $id = Input::get('id');
            $ce_proconestprevio = CeProcontractuale::whereId($id)->with([
                'estudiosPrevios.actividades',
                'estudiosPrevios.forpagos',
                'estudiosPrevios.garantias',
                'estudiosPrevios.tipoContratacion',
                'estudiosPrevios.imputacionPresupuestal.strgasto.rubro',
                'estudiosPrevios.modalidad',
                'estudiosPrevios.proconminutageos.municipio',
                'estudiosPrevios.proconminutageos.upcservicios.servicio',
                'estudiosPrevios.proconminutageos.upcedades.rangoupc',
                'estudiosPrevios.funcionario_registro',
                'estudiosPrevios.funcionario_reviso',
                'estudiosPrevios.funcionario_confirmo',
                'contrato'
            ])->first();

            list($geocons, $geosubs) = $this->getTarifas($ce_proconestprevio);

//            $usuario_proyecta_estudio = ThEncargo::where([
//                ['tipo_encargo', 'LIKE', 'Profesional de Apoyo DAF'],
//                ['estado', '=', 1],
//            ])->orderBy('fecha_fin', 'desc')->first();
//
//            $usuario_revisa_estudio = ThEncargo::where([
//                ['tipo_encargo', 'LIKE', 'Profesional Especializado DSS-DAF'],
//                ['estado', '=', 1],
//            ])->orderBy('fecha_fin', 'desc')->first();
//
//            $usuario_aprueba_estudio = ThEncargo::where([
//                ['tipo_encargo', 'LIKE', 'Subgerente División de Servicios de Salud'],
//                ['estado', '=', 1],
//            ])->orderBy('fecha_fin', 'desc')->first();

            $ce_proconestprevio->estudiosPrevios['desc_plazo'] = str_replace('&nbsp;', '', $ce_proconestprevio->estudiosPrevios['desc_plazo']);

            $datos = [
                'ce_proconestprevio' => $ce_proconestprevio,
                'geocons' => $geocons,
                'geosubs' => $geosubs,
                'usuario_proyecta' => $ce_proconestprevio->estudiosPrevios->funcionario_registro,
                'usuario_revisa' => $ce_proconestprevio->estudiosPrevios->funcionario_reviso,
                'usuario_aprueba' => $ce_proconestprevio->estudiosPrevios->funcionario_confirmo,
            ];

            if (Input::get('html')) {
                return view('dompdf.estudioPrevioPDF', $datos);
            }
            setlocale(LC_ALL, "es_ES");
            //return view('dompdf.estudioPrevioPDF', ['ce_proconestprevio' => $ce_proconestprevio]);
            $pdf = PDF::loadView('dompdf.estudioPrevioPDF', $datos);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('ESTUDIO PREVIO SERVICIOS DE SALUD', ['Attachment' => 0]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    private function copiaRelaEstudioPrevio($data, $FkEstPrevio)
    {
        $items = array();
        foreach ($data as $datum) {
            $datum['id'] = null;
            $datum[$FkEstPrevio] = null;
            array_push($items, $datum);
        }

        return $items;
    }

    /**
     * @param $ce_procontractuale
     * @param $data
     * @param array $imputaciones
     * @param array $actividades
     * @param array $garantias
     * @param array $forpagos
     * @return array
     */
    public function getArrEstudioPrevio(
        $ce_procontractuale,
        $data,
        array $imputaciones,
        array $actividades,
        array $garantias,
        array $forpagos
    ): array
    {
        $proconestprevio = [
            'id' => null,
            'ce_procontractuale_id' => isset($ce_procontractuale) ? $ce_procontractuale->id : null,
            'consecutivo' => null,
            'fecha' => Carbon::now()->format('Y-m-d'),
            'desc_necesidad' => $data['desc_necesidad'],
            'desc_tecnica' => $data['desc_tecnica'],
            'esp_tecnicas' => $data['esp_tecnicas'],
            'sop_economico' => $data['sop_economico'],
            'alt_ejecucion' => $data['alt_ejecucion'],
            'pos_riesgos' => $data['pos_riesgos'],
            'ce_tipcontratacione_id' => $data['ce_tipcontratacione_id'],
            'valor' => $data['valor'],
            'supervicion' => $data['supervicion'],
            'descripgarantias' => $data['descripgarantias'],
            'lugar_ejecucion' => $data['lugar_ejecucion'],
            'productos_entregar' => $data['productos_entregar'],
            'plazo_meses' => $data['plazo_meses'],
            'plazo_dias' => $data['plazo_dias'],
            'tarifas' => $data['tarifas'],
            'supervisor_id' => $data['supervisor_id'],
            'pr_solicitud_cp_id' => $data['pr_solicitud_cp_id'],
            'ce_tipocontrato_id' => $data['ce_tipocontrato_id'],
            'estado' => $data['estado'],
            'registro' => $data['registro'],
            'reviso' => $data['reviso'],
            'confirmo' => $data['confirmo'],
            'revisado_at' => $data['revisado_at'],
            'confirmado_at' => $data['confirmado_at'],
            'imputacion_presupuestal' => $imputaciones,
            'actividades' => $actividades,
            'garantias' => $garantias,
            'forpagos' => $forpagos
        ];

        return $proconestprevio;
    }

    /**
     * @param $ce_proconestprevio
     * @return array
     */
    private function getTarifas($ce_proconestprevio): array
    {
        $geosubs = [];
        $geocons = [];

        foreach ($ce_proconestprevio->estudiosPrevios['proconminutageos'] as $proconminutageo) {
            if (count($proconminutageo['upcservicios']) > 0) {
                foreach ($proconminutageo['upcservicios'] as $upcservicio) {
                    switch ($ce_proconestprevio->estudiosPrevios['modalidad']['id']) {
                        case 1:
                            if ($upcservicio['porcent_pob_contributivo'] !== null) {
                                $valor_contributivo = round(($proconminutageo['valor_upc_contributivo'] * ($upcservicio['porcent_pob_contributivo'] / 100)), 2);
                                $data1 = [
                                    'municipio' => $proconminutageo['municipio']['nombre'],
                                    'servicio' => $upcservicio['servicio']['nombre'],
                                    'porcentaje_contributivo' => $proconminutageo['porcentaje_contributivo'],
                                    'valor_upc_contributivo' => $proconminutageo['valor_upc_contributivo'],
                                    'porcent_pob_contributivo' => $upcservicio['porcent_pob_contributivo'],
                                    'percapita' => $valor_contributivo,
                                    'valor_x_tiempo' => 0
                                ];
                                array_push($geocons, $data1);
                            }

                            if ($upcservicio['porcent_pob_subsidiado'] !== null) {
                                $valor_subsidiado = round(($proconminutageo['valor_upc_subsidiado'] * ($upcservicio['porcent_pob_contributivo'] / 100)), 2);
                                $data2 = [
                                    'municipio' => $proconminutageo['municipio']['nombre'],
                                    'servicio' => $upcservicio['servicio']['nombre'],
                                    'porcentaje_subsidiado' => $proconminutageo['porcentaje_subsidiado'],
                                    'valor_upc_subsidiado' => $proconminutageo['valor_upc_subsidiado'],
                                    'portabilidad' => round(100, 2),
                                    'porcent_pob_subsidiado' => $upcservicio['porcent_pob_subsidiado'],
                                    'percapita' => $valor_subsidiado,
                                    'valor_x_tiempo' => 0
                                ];
                                array_push($geosubs, $data2);
                            }
                            break;
                        case 9:
                            $data1 = [
                                'municipio' => $proconminutageo['municipio']['nombre'],
                                'servicio' => $upcservicio['servicio']['nombre'],
                                'porcentaje_contributivo' => $proconminutageo['porcentaje_contributivo'],
                                'valor_upc_contributivo' => $proconminutageo['valor_upc_contributivo'],
                                'porcentaje_subsidiado' => $proconminutageo['porcentaje_subsidiado'],
                                'valor_upc_subsidiado' => $proconminutageo['valor_upc_subsidiado'],
                                'valor' => $upcservicio['valor']
                            ];
                            array_push($geocons, $data1);
                            break;
                    }
                }
            }
        }

        return [$geocons, $geosubs];
    }
}
