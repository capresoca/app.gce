<?php

namespace App\Http\Resources\RedServicios;

use Illuminate\Http\Resources\Json\JsonResource;

class CumResource extends JsonResource
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
