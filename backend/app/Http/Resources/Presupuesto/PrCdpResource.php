<?php

namespace App\Http\Resources\Presupuesto;

use Illuminate\Http\Resources\Json\JsonResource;

class PrCdpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        parent::toArray($request);
        return [
            'id' => $this->id,
            'consecutivo' => $this->consecutivo,
            'periodo' => $this->periodo,
            'fecha' => $this->fecha,
            'fecha_vence' => $this->fecha_vence,
            'objecto' => $this->objecto,
            'suma_valores_ejecutados' => $this->suma_valores_ejecutados,
            'estado' => $this->estado,
            'dependencia' => $this->dependencia ? $this->dependencia : null,
            'valor_cdp' => (double) $this->valor_cdp
        ];
    }
}
