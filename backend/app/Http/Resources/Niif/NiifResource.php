<?php

namespace App\Http\Resources\Niif;

use Illuminate\Http\Resources\Json\JsonResource;

class NiifResource extends JsonResource
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
