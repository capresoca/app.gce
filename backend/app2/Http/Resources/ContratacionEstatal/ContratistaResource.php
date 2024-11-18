<?php

namespace App\Http\Resources\ContratacionEstatal;

use Illuminate\Http\Resources\Json\JsonResource;

class ContratistaResource extends JsonResource
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
        $resource['tercero'] = $this->tercero;
        return $resource;
    }
}
