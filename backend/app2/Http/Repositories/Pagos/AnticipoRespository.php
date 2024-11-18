<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/06/2018
 * Time: 3:45 PM
 */

namespace App\Http\Repositories\Pagos;


use App\Http\Repositories\Repository;
use App\Models\Pagos\PgAnticipo;
use App\Models\Pagos\PgSaliniciale;
use App\Models\Tesoreria\TsCompegreso;
use App\Models\Tesoreria\TsCompegresoConcepto;
use App\Models\Tesoreria\TsConceptoegresoDetalle;
use Carbon\Carbon;

class AnticipoRespository implements Repository
{

    public function guardar($requestArray)
    {
        // TODO: Implement guardar() method.
        if (!isset($requestArray['id'])) {
            $pg_anticipo = PgAnticipo::create($requestArray);
        } else {
            $pg_anticipo = PgAnticipo::findOrFail($requestArray['id']);
            $pg_anticipo->update($requestArray);
        }
        return $pg_anticipo;
    }

    public function validar($requestArray)
    {
        // TODO: Implement validar() method.
        $rules = [
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'nf_niifanticipo_id' => 'required|exists:nf_niifs,id',
            'valor' => 'required',
            'fecha_documento' => 'required|date',
            'observacion' => 'required'
        ];

        if ($requestArray['ts_compegreso_id'] !== null){
            $rules['nf_cencosto_id'] = 'required|exists:nf_cencostos,id';
        }

        return $rules;
    }

    public function createArrayAnticipo ($id, $request, $num) {

        if ($num === 1) {
            $saldo = PgSaliniciale::with('proveedor.tercero','niif')->whereId($id)->first();
            $anticipo = [
                'gn_tercero_id' => isset($saldo->proveedor) ? $saldo->proveedor['tercero']['id'] : null,
                'nf_niifanticipo_id' => isset($saldo->niif) ? $saldo->niif['id'] : null,
                'nf_cencosto_id' => null,
                'ts_compegreso_id' => null,
                'pg_saldoinicial_id' => $saldo->id,
                'origen' => 'Saldos Iniciales',
                'estado_documento' => $saldo->estado,
                'fecha_documento' => $request->fecha_anticipo,
                'observacion' => 'Saldos Iniciales | No. '.str_pad($saldo->consecutivo,6,"0",STR_PAD_LEFT),
                'valor' => (double) $saldo->valor
            ];
        } else {
            $detalle_concepto = TsConceptoegresoDetalle::whereId($id)->first();
            $detalle_comEgreso = TsCompegresoConcepto::with('concepto')->whereId($detalle_concepto['ts_compegreso_concepto_id'])->first();
            $comEgreso = TsCompegreso::find($detalle_comEgreso['ts_compegreso_id']);
            $anticipo = [
                'gn_tercero_id' => $comEgreso->gn_tercero_id,
                'nf_niifanticipo_id' =>  isset($detalle_comEgreso['concepto']['niif']) ? $detalle_comEgreso['concepto']['niif']['id'] : null,
                'nf_cencosto_id' => $comEgreso->nf_cencosto_id ?? null,
                'ts_concepoegreso_detalle_id' => $detalle_concepto['id'],
                'pg_saldoinicial_id' => null,
                'origen' => 'Egreso',
                'estado_documento' => $comEgreso->estado,
                'fecha_documento' => Carbon::parse($request->fecha)->format('Y-m-d'),
                'observacion' => 'Comprobante de Egreso | No. '.str_pad($comEgreso->consecutivo,6,"0",STR_PAD_LEFT),
                'valor' => (double) $detalle_concepto['valor'],
                'ts_compegreso_id' => $detalle_comEgreso['ts_compegreso_id']
            ];
            //$anticipo = $request['anticipo'];
        }

        $this->validar($anticipo);
        $this->guardar($anticipo);

        return $anticipo;
    }


    public function updateArrayAnticipo ($id, $request, $anticipoExiste) {
        $detalle_concepto = TsConceptoegresoDetalle::whereId((integer) $id)->first();
        $detalle_comEgreso = TsCompegresoConcepto::with('concepto')->whereId($detalle_concepto['ts_compegreso_concepto_id'])->first();
        $comEgreso = TsCompegreso::find($detalle_comEgreso['ts_compegreso_id']);
        $anticipo = [
            'id' => $anticipoExiste['id'],
            'gn_tercero_id' => $comEgreso->gn_tercero_id,
            'nf_niifanticipo_id' =>  isset($detalle_comEgreso['concepto']['niif']) ? $detalle_comEgreso['concepto']['niif']['id'] : null,
            'nf_cencosto_id' => $comEgreso->nf_cencosto_id ?? null,
            'estado_documento' => $request->estado,
            'ts_concepoegreso_detalle_id' => $detalle_concepto['id'],
            'fecha_documento' => Carbon::parse($request->fecha)->format('Y-m-d'),
            'valor' => (double) $detalle_concepto['valor'],
            'observacion' => 'Comprobante de Egreso | No. '.str_pad($comEgreso->consecutivo,6,"0",STR_PAD_LEFT),
            'ts_compegreso_id' => $detalle_comEgreso['ts_compegreso_id']
        ];

        $this->validar($anticipo);
        $this->guardar($anticipo);

        return $anticipo;
    }

}