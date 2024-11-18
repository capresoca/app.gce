<?php

namespace App\Http\Resources\Autorizaciones;

use Illuminate\Http\Resources\Json\JsonResource;

class SolautorizacionesResource extends JsonResource
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
            'nombre_completo' => !$this->afiliado ? null : $this->afiliado->nombre_completo,
            'identificacion_completa' => !$this->afiliado ? null : $this->afiliado->identificacion_completa,
            'ips_origen' => !$this->entidadOrigen ? null : $this->entidadOrigen->tercero->nombre_completo,
            'ips_destino' => !$this->entidadDestino ? null : $this->entidadDestino->tercero->nombre_completo,
            'fecha' => $this->fecha,
            'estado' => $this->estado,
            'consecutivo' => $this->consecutivo,
            'mini_afiliado' => $this->afiliado ? $this->afiliado->mini_afiliado : null
        ];
    }
}
