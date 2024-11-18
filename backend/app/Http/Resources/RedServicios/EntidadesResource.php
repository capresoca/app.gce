<?php

namespace App\Http\Resources\RedServicios;

use Illuminate\Http\Resources\Json\JsonResource;

class EntidadesResource extends JsonResource
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
            'gn_tercero_id' => $this->whenLoaded('tercero',$this->tercero->id),
            'nombre_razon_social' => $this->nombre,
            'nombre_completo' => $this->nombre,
            'identificacion' => $this->whenLoaded('tercero',$this->tercero->tipo_id->abreviatura .' '. $this->tercero->identificacion),
            'identificacion_completa' => $this->whenLoaded('tercero',$this->tercero->tipo_id->abreviatura .' '. $this->tercero->identificacion),
            'tipo' => $this->whenLoaded('tipo',$this->tipo->tipo),
            'cod_habilitacion' => $this->cod_habilitacion,
            'municipio' => $this->municipio ? $this->municipio->nombre : null
        ];
    }
}