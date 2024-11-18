<?php

namespace App\Http\Resources\Aserguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class DepuracionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resources = [
            'id'               => $this->id,
            'mini_afiliado'    => $this->afiliado['mini_afiliado'] ?? null,
            'identificacion'   => $this->identificacion,
            'estado_proceso'   => $this->estado_proceso,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'municipio'        => $this->municipio['nombre'],
            'departamento'        => $this->municipio['nombre_departamento']
        ];
        return $resources;
        //return parent::toArray($request);
    }
}
