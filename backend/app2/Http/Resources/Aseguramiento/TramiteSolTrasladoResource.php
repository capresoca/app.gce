<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class TramiteSolTrasladoResource extends JsonResource
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
            'nombre_afiliado' => $this->solicitudTraslado->afiliado->nombre_completo,
            'identificacion' => $this->solicitudTraslado->afiliado->identificacion_completa,
            'fecha_solicitada' => $this->solicitudTraslado->fecha_solicita,
            'eps' => $this->solicitudTraslado->eps->nombre,
            'respuesta' => $this->solicitudTraslado->respuesta,
            'estado' => $this->solicitudTraslado->estado,
            'causa_traslado' => $this->solicitudTraslado->causa,
            'fecha_factible' => $this->solicitudTraslado->fecha_factible
        ];
    }
}
