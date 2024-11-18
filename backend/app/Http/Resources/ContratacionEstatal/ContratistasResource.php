<?php

namespace App\Http\Resources\ContratacionEstatal;

use Illuminate\Http\Resources\Json\JsonResource;

class ContratistasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'identificacion' => $this->tercero->identificacion_completa,
            'nombre_contratista' => $this->tercero->nombre_completo,
            'fecha_ccomercio' => $this->fecha_ccomercio,
            'ccomercio' => $this->ccomercio,
            'nombre_natjuridica' => $this->natJuridica->nombre,
            'gn_tercero_id' => $this->gn_tercero_id
        ];
        return $resource;
    }
}
