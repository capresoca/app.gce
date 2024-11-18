<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class NovtramitesResource extends JsonResource
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
            'tramite_id' => $this->tramite->id,
            'identificacion' => $this->afiliado ? $this->afiliado->identificacion_completa :  '',
            'afiliado' => $this->afiliado ? $this->afiliado->nombre_completo : '',
            'novedad' => $this->novedad ? $this->novedad : '',
            'fecha' => $this->tramite ? $this->tramite->fecha : '',
            'fecha_radicacion' => $this->tramite ? $this->tramite->fecha_radicacion : '',
            'estado' => $this->tramite->estado,
            'mini_afiliado' => $this->afiliado ? $this->afiliado->mini_afiliado : ''
        ];
    }
}
