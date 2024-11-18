<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class SoltrasladosResource extends JsonResource
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
            'nombre_afiliado' => $this->afiliado->nombre_completo,
            'identificacion' => $this->afiliado->identificacion_completa,
            'fecha_solicitada' => $this->fecha_solicita,
            'eps' => ($this->eps!=null ? $this->eps->nombre : ''),
            'respuesta' => $this->respuesta,
            'estado' => $this->estado,
            'as_cautraslado_id' => $this->as_cautraslado_id,
            'fecha_factible' => $this->fecha_factible,
            'dias_eps' => $this->dias_eps,
            'mini_afiliado' => $this->afiliado ? $this->afiliado->mini_afiliado : null
        ];
    }
}
