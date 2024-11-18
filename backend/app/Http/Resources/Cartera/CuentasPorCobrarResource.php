<?php

namespace App\Http\Resources\Cartera;

use Illuminate\Http\Resources\Json\JsonResource;

class CuentasPorCobrarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'consecutivo' => $this->consecutivo,
            'nombre' => $this->cliente ? $this->cliente->nombre : 'No Registra',
            'identificacion_completa' => $this->cliente ? $this->cliente->tercero->identificacion_completa : 'No Registra',
            'numero_factura' => $this->numero_factura,
            'fecha_factura' => $this->fecha_factura,
            'fecha' => $this->fecha,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'estado' => $this->estado
        ];
        return $resource;
    }
}
