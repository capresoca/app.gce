<?php

namespace App\Http\Resources\Pagos;

use Illuminate\Http\Resources\Json\JsonResource;

class PgUniapoyoResource extends JsonResource
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