<?php

namespace App\Http\Resources\Pagos;

use Illuminate\Http\Resources\Json\JsonResource;

class PgConpagoResource extends JsonResource
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
        $resource['codigo_nombre'] = $this->codigo_nombre;
        return $resource;
    }
}
