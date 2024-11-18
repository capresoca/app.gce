<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class AsAfitramitesResource extends JsonResource
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
            'identificacion' => $this->afiliacion->afiliado->identificacion_completa,
            'nombre' => $this->afiliacion->afiliado->nombre_completo,
            'fecha_afiliacion' => $this->fecha_radicacion,
            'municipio' => $this->afiliacion->afiliado->gn_municipio_id ?
                            $this->afiliacion->afiliado->municipio->nombre : ''
        ];
    }
}