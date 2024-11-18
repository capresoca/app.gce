<?php

namespace App\Http\Resources\RedServicios;

use Illuminate\Http\Resources\Json\JsonResource;

class ContratoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['numero_contrato'] = !$this->contrato ? null : $this->contrato->numero_contrato ;
        $data['regimen'] = !$this->regimen ? null : $this->regimen->nombre;

        return $data;
    }
}
