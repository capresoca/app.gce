<?php

namespace App\Http\Resources\Cartera;

use Illuminate\Http\Resources\Json\JsonResource;

class ConceptoResource extends JsonResource
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