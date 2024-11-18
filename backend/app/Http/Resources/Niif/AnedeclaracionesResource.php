<?php

namespace App\Http\Resources\Niif;

use Illuminate\Http\Resources\Json\JsonResource;

class AnedeclaracionesResource extends JsonResource
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
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'estado' => $this->estado,
            'removable' => $this->niifs->count() ? false : true
        ];
    }
}
