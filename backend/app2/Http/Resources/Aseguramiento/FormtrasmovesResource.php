<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class FormtrasmovesResource extends JsonResource
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
            'identificacion' => $this->afiliado->identificacion_completa,
            'afiliado' => $this->afiliado->emoticon.$this->afiliado->nombre_completo,
            'mini_afiliado' => $this->afiliado ? $this->afiliado->mini_afiliado : null,
            'estado' => $this->estado,
            'nombreEps' => $this->eps ? $this->eps->nombre : null,
            'fecha_traslado' => $this->fecha_traslado
        ];

    }
}
