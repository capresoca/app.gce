<?php

namespace App\Http\Resources\ContratacionEstatal;

use Illuminate\Http\Resources\Json\JsonResource;

class BienserviciosResource extends JsonResource
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
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'nombre_clase' => $this->clasbienservicio->nombre,
            'nombre_familia' => $this->clasbienservicio->fambienservicio->nombre,
            'nombre_segmento' => $this->clasbienservicio->fambienservicio->segbienservicio->nombre,
            'estado' => $this->estado
        ];
        return $resource;
    }
}
