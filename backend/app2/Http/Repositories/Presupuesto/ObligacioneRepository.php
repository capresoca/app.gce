<?php
/**
 * Created by PhpStorm.
 * User: Jorge Eduardo
 * Date: 14/03/2019
 * Time: 11:02 AM
 */

namespace App\Http\Repositories\Presupuesto;

use App\Http\Repositories\Repository;
use App\Models\Pagos\PgCxp;
use App\Models\Presupuesto\PrDetrp;
use App\Models\Presupuesto\PrEntidadResolucione;
use App\Models\Presupuesto\PrObligacione;
use App\Traits\EnumsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ObligacioneRepository implements Repository
{

    public function guardar($requestArray)
    {
        if(!isset($requestArray['id'])) {
            $pr_obligacione = PrObligacione::create($requestArray);
            $pr_obligacione->detalles()->createMany($requestArray['detalles']);
        } else {
            $pr_obligacione = PrObligacione::findOrFail($requestArray['id']);
            $pr_obligacione->update($requestArray);
            $pr_obligacione->detalles()->delete();
            $pr_obligacione->detalles()->createMany($requestArray['detalles']);
        }
//        dd($pr_obligacione);
        return $pr_obligacione;
    }

    public function validar($requestArray)
    {
//        dd($requestArray);
        $today = Carbon::today()->format('Y-m-d');
        $rules = [
            'fecha' => 'required|date|after_or_equal:' . $today,
            'fecha_documento' => 'required|date',
            'valor_obligacion' => 'required',
            'pr_entidad_resolucion_id' => 'required|exists:pr_entidad_resoluciones,id',
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'estado' => 'required|in:' . implode(',', EnumsTrait::getEnumValues('pr_obligaciones','estado')),
            'detalles' => 'required'
        ];
        $messages = [];
////        dd($requestArray['detalles']);
        foreach ($requestArray['detalles'] as $key => $detalle) {
            if (!isset($detalle['pr_detrp_id'])) {
                $rules['detalles.'.$key.'pr_detrp_id'] = 'required|exists:pr_detrps,id';
            }
            if (!isset($detalle['pr_cdp_id'])) {
                $rules['detalles.'.$key.'pr_cdp_id'] = 'required|exists:pr_cdps,id';
            }
            if (!isset($detalle['pr_rubro_id'])) {
                $rules['detalles.'.$key.'pr_rubro_id'] = 'required|exists:pr_conceptos,id';
            }
            if (!isset($detalle['pr_tipo_gasto_id'])) {
                $rules['detalles.'.$key.'pr_tipo_gasto_id'] = 'required|exists:pr_tipos_gastos,id';
            }
            if($detalle['valor'] === '' || $detalle['valor'] === null) {
                $rules['detalles.'.$key.'valor'] = 'required';
            }
            if($detalle['saldo_por_obligar'] === null) {
                $rules['detalles.'.$key.'saldo_por_obligar'] = 'required';
            }
        }
//
        $messages = [
            'detalles.1.pr_detrp_id.required' => 'Se debe agregar el registro presupuestal.',
            'detalles.1.pr_cdp_id.required' => 'Se debe agregar el CDP del registro presupuestal',
            'detalles.1.pr_rubro_id.required' => 'Se debe agregar el rubro.',
            'detalles.1.pr_tipo_gasto_id.required' => 'Se debe agregar el tipo de gasto.',
            'detalles.*.saldo_por_obligar.required' => 'Se debe almacenar el valor del saldo del rubro RP',
            'detalles.*.valor.required' => 'El valor debe estar designado.',
            'detalles.required' => 'Debe existir al menos un detalle.'
        ];

        $validator = Validator::make($requestArray, $rules, $messages);
//        dd($validator);

        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }
    }

    public function createFromCxp ($id, $request) {
        $cxp = PgCxp::with('detalles',
            'obligacion.detalles.rubro',
            'obligacion.detalles.tipoGasto',
            'obligacion.detalles.cdp',
            'obligacion.detalles.rp',
            'obligacion.detalles.detalle_rp')->where('id', $id)->first();
        $periodoActual = Carbon::now()->format('Y');
        $entidadResolucion = PrEntidadResolucione::wherePeriodo($periodoActual)->first();
        $obligacionCxp = [
            'periodo' => $periodoActual,
            'documento' => 'CXP No. ' . str_pad($cxp->consecutivo,6,"0",STR_PAD_LEFT),
            'observaciones' => $cxp->observaciones,
            'fecha' => Carbon::parse($cxp->fecha_causacion)->format('Y-m-d'),
            'fecha_documento' => Carbon::parse($cxp->fecha_causacion)->format('Y-m-d'),
            'estado' => 'Confirmada',
            'pr_entidad_resolucion_id' => $entidadResolucion->id,
            'gn_tercero_id' => $cxp->gn_tercero_id,
            'pg_cxp_id' => $cxp->id,
            'valor_obligacion' => isset($cxp->obligacion) ? $cxp->obligacion->valor_obligacion : $request->valor_obligacion_header,
            'detalles' => $this->detallesCxp($cxp, (isset($cxp->obligacion) ? $cxp->obligacion->detalles : $request->rubrosDetalles))
        ];

        $this->guardar($obligacionCxp);

        return $obligacionCxp;
    }

    public function detallesCxp ($cxp, $rubrosDetalles)
    {
        $detalles = [];
        foreach ($rubrosDetalles as $detalle) {
            $rubro = PrDetrp::with('rubro')->whereId((int) $detalle['pr_detrp_id'])->first();
            array_push($detalles, [
                'fecha_rp' => isset($detalle['rp']) ? $detalle['rp']['fecha'] : null,
                'saldo_por_obligar' => $detalle['saldo_por_obligar'],
                'valor' => (double) $detalle['valor'],
                'pr_tipo_gasto_id' => $detalle['pr_tipo_gasto_id'],
                'pr_detrp_id' => $detalle['pr_detrp_id'],
                'pr_cdp_id' => isset($detalle['cdp']) ? $detalle['cdp']['id'] : null,
                'pr_rp_id' => isset($detalle['rp']) ? $detalle['rp']['id'] : null,
                'pr_rubro_id' => isset($rubro) ? $rubro->rubro->id : null
            ]);
        }
        $this->creActualizaDetalleRp($detalles);
        return $detalles;
    }

    public function creActualizaDetalleRp ($detalles) {
        try {
            foreach ($detalles as $detalle) {
                $id = (int) $detalle['pr_detrp_id'];
                $detrp = PrDetrp::find($id);
                $valorEjecutado = $detrp->valor_ejecutado;
                $valorIngresado = (double) $detalle['valor'];
                $detrp->valor_ejecutado = ($valorEjecutado !== 0) ? $valorEjecutado + $valorIngresado : $valorIngresado;
                $detrp->save();
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Error al cambiar el valor en ejecución en el detalle del registro presupuestal.'
            ], 500);
        }
    }

}
