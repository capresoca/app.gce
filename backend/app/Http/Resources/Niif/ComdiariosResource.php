<?php

namespace App\Http\Resources\Niif;

use Illuminate\Http\Resources\Json\JsonResource;

class ComdiariosResource extends JsonResource
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
        $resource['tipo'] = $this->tipo ? $this->tipo->nombre : '';
        $resource['codigoTipo'] = $this->tipo ? $this->tipo->codigo : '';
        $resource['valor'] = $this->valor;
        return $resource;
    }
}
