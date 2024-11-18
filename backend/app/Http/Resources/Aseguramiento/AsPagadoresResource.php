<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class AsPagadoresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'gn_tercero_id' => $this->tercero? $this->tercero->id : '',
            'nombre_razon_social' => $this->razon_social,
            'identificacion' => $this->identificacion,
            'tipo_aportante_cod' => $this->tipo_arportante ?  $this->tipo_aportante->codigo : '',
            'tipo_aportante' => $this->tipo_arportante ? $this->tipo_aportante->nombre : ''
        ];
    }
}
