<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Pagos\AnticipoRespository;
use App\Http\Requests\Tesoreria\CompegresoRequest;
use App\Http\Resources\Tesoreria\CompegresoResource;
use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\Niif\GnTercero;
use App\Models\Pagos\PgAnticipo;
use App\Models\Tesoreria\TsCompegreso;
use App\Models\Tesoreria\TsCompegresoConcepto;
use App\Models\Tesoreria\TsConceptoEgreso;
use App\Models\Tesoreria\TsConceptoegresoDetalle;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class CompegresoController extends Controller
{
    public function index()
    {
        return CompegresoResource::collection(
            TsCompegreso::with(
                TsCompegreso::allRelations()
            )->paginate(Input::get('per_page'))
        );
    }
    
    public function store(CompegresoRequest $compegresoRequest)
    {
        $compegreso = TsCompegreso::create($compegresoRequest->all());
        $this->createOrUpdateConComDetalle($compegresoRequest, $compegreso);
        $compegreso->load(TsCompegreso::allRelations());
        return response(new CompegresoResource($compegreso))->setStatusCode(201);
    }

    public function update(TsCompegreso $compegreso, CompegresoRequest $compegresoRequest)
    {
        $compegreso->update($compegresoRequest->all());
        $this->createOrUpdateConComDetalle($compegresoRequest, $compegreso);
        //$compegreso->conceptos()->delete();
        //
        //foreach ($compegresoRequest->conceptos as $concepto) {
        //    //validar concepto
        //    $conceptoComp = $compegreso->conceptos()->create($concepto);
        //    foreach ($concepto['detalles'] as $detalle){
        //        $conceptoComp->detalles()->create($detalle);
        //    }
        //}

        $compegreso->load(TsCompegreso::allRelations());
        return response()->json(['data' => new CompegresoResource($compegreso)])->setStatusCode(200);
    }

    public function show(TsCompegreso $compegreso)
    {
         return new Resource($compegreso->load(TsCompegreso::allRelations()));
    }

    public function getAnticiposAndContratosTercero (GnTercero $tercero, TsConceptoEgreso $conegreso) {
        try {
            if ($conegreso->anticipos === 1) {
                $anticipos = PgAnticipo::with('cuenta','saldoIncial')
                    ->where('gn_tercero_id', '=', $tercero->id)
                    ->where('estado_documento','=','Confirmado')->get();
            }

            $contratos = CeProconminuta::with('planes_cobertura','entidad.tercero')
                ->whereHas('entidad.tercero', function ($query) use ($tercero) {
                    $query->where('id','=',$tercero['id']);
            })->get();

            return response()->json([
                'anticipos' => $anticipos ?? [],
                'contratos' => $contratos ?? []
            ],200);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getPDF() {
        try {
            $id = (integer) Input::get('id');
            $comprobante = TsCompegreso::where('id', $id)->first();
//            $comdiario->load(['detalles.tercero', 'detalles.cencosto', 'detalles.conrtf', 'detalles.niif', 'tipo','usuario']);
            if (Input::get('html')) {
                return view('dompdf.comproEgresoPdf');
            }

            $pdf = PDF::loadView('dompdf.comproEgresoPdf', ['comprobante' => $comprobante]);
            $pdf->setPaper('letter', 'portrait');

            return $pdf->stream("Comprobante de Egreso N° $comprobante->consecutivo", ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param \App\Http\Requests\Tesoreria\CompegresoRequest $compegresoRequest
     * @param $compegreso
     */
    private function createOrUpdateConComDetalle(CompegresoRequest $compegresoRequest, TsCompegreso $compegreso): void
    {
        foreach ($compegresoRequest->conceptos as $concepto) {
            //todo validar concepto
            $conceptoComp = isset($concepto['id']) ? TsCompegresoConcepto::find($concepto['id']) : new TsCompegresoConcepto ();
            $conceptoComp->ts_compegreso_id = $compegreso->id;
            $conceptoComp->ts_concepto_egreso_id = $concepto['ts_concepto_egreso_id'];
            $conceptoComp->save();
            $conceptoComp->load('concepto');
            foreach ($concepto['detalles'] as $detalle) {
                if ($detalle['id'] !== null) {
                    $comdetconepto = TsConceptoegresoDetalle::with('anticipo')->find((integer) $detalle['id']);
                    $comdetconepto->update((array) $detalle);
                    //$detalleConcepto = $conceptoComp->detalles()->update($detalle);
                } else {
                    $detalleConcepto = $conceptoComp->detalles()->create([
                        'pg_cxp_id' => isset($detalle['pg_cxp_id']) ? $detalle['pg_cxp_id'] : null,
                        'rs_planescobertura_id' => isset($detalle['rs_planescobertura_id']) ? $detalle['rs_planescobertura_id'] : null,
                        'valor' => (double) $detalle['valor']
                    ]);
                }
                if (isset($detalle['rs_planescobertura_id'])
                    || (is_null('pg_cxp_id') && is_null($detalle['rs_planescobertura_id']))) {
                    $anticipoRepocitory = new AnticipoRespository();
                    if ($detalle['id'] !== null) {
                        if (isset($comdetconepto->anticipo)) {
                            $anticipoRepocitory->updateArrayAnticipo($comdetconepto['id'], $compegresoRequest, $comdetconepto->anticipo);
                        } else {
                            $anticipoRepocitory->createArrayAnticipo($comdetconepto['id'], $compegresoRequest, 2);
                        }
                    } else {
                        $anticipoRepocitory->createArrayAnticipo($detalleConcepto['id'], $compegresoRequest, 2);
                    }
                }
            }
        }
    }
}