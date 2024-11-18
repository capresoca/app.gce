<?php

namespace App\Http\Resources\Niif;

use Illuminate\Http\Resources\Json\JsonResource;

class ConrtfResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $resource = parent::toArray($request);
        $resource['detalles'] = $this->detalles;
        $resource['codigo_nombre'] = $this->codigo_nombre;
        return $resource;
    }
}
