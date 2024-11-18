<?php

namespace App\Http\Resources\Presupuesto;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoIngresoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
