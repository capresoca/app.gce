<?php

namespace App\Http\Resources\Riips;

use Illuminate\Http\Resources\Json\JsonResource;

class RiipsResource extends JsonResource
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
