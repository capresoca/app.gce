<?php

namespace App\Http\Resources\Pagos;

use Illuminate\Http\Resources\Json\JsonResource;

class PgCxpsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'consecutivo' => $this->consecutivo,
            'fecha_causacion' => $this->fecha_causacion,
            'nombre_completo' => $this->proveedor->tercero->nombre_completo,
            'identificacion_completa' => $this->proveedor->tercero->identificacion_completa,
            'no_factura' => $this->no_factura,
            'estado' => $this->estado,
            'valor_moneda' => $this->valor_moneda,
            'valor_debito_moneda' => $this->valor_debito_moneda,
            'valor_credito_moneda' => $this->valor_credito_moneda,
            'detalle_anulacion' => $this->detalle_anulacion,
            'valor_sin_formato' => $this->valor_sin_formato,
            'modulo' => is_null($this->modulo) ? '' : $this->modulo
        ];
    }
}
