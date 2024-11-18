<?php

namespace App\Http\Resources\OficinaJuridica;

use Illuminate\Http\Resources\Json\JsonResource;

class TutelasResource extends JsonResource
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
            'no_tutela' => $this->no_tutela,
            'identificacion_completa' => $this->tipIdentidad->abreviatura . ' ' . $this->identificacion,
            'nombre' => $this->nombre,
            'fecha' => $this->fecha,
            'fecha_notificacion' => $this->fecha_notificacion,
            'nombre_afiliado'   => $this->afiliado ? $this->afiliado->nombre_completo : null,
            'nombre_juzgado'    => $this->juzgado->nombre,
            'estado'            => $this->estado,
            'afiliados_tutelas' => $this->afiliados_tutelas,
            'url_signed'        => $this->archivo ? $this->archivo->url_signed : null,
        ];
        return $resource;
    }
}
