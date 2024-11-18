<?php

namespace App\Http\Resources\Autorizaciones;

use Illuminate\Http\Resources\Json\JsonResource;

class AutorizacionesResource extends JsonResource
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
            'id'             => $this->id,
            'nombre_afiliado'=> $this->afiliado->nombre_completo,
            'identificacion' => $this->afiliado->identificacion_completa,
            'origen'         => $this->entidad_origen->nombre,
            'servicio'       => !$this->servicio ? null : $this->servicio->servicio->nombre,
            'destino'        => $this->contrato->contrato->entidad->nombre,
            'estado'         => $this->estado,
            'mini_afiliado' => $this->afiliado->mini_afiliado
        ];
    }
}
