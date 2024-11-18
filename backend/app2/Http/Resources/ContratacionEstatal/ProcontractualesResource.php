<?php

namespace App\Http\Resources\ContratacionEstatal;

use Illuminate\Http\Resources\Json\JsonResource;

class ProcontractualesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resources = [
            'id' => $this->id,
            'consecutivo' => $this->consecutivo,
            'objeto' => $this->objeto,
            'dependencia' => $this->dependencia ? $this->dependencia->nombre : null,
            'es_servicio_salud' => ($this->servicios_salud == 1) ? true : false,
            'estado' => $this->estado,
            'estudio_previo_id' => $this->estudiosPrevios ? $this->estudiosPrevios->id : null
        ];
        return $resources;
    }
}
