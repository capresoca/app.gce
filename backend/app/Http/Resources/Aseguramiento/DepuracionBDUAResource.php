<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class DepuracionBDUAResource extends JsonResource
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
            'id'               => $this->consecutivo_ns,
            'mini_afiliado'    => $this->afiliado['mini_afiliado'] ?? null,
            'identificacion'   => $this->identificacion,
            'estado_proceso'   => $this->estado_procesado,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'municipio'        => $this->municipio,
            'departamento'     => $this->departamento
        ];
        return $resources;
    }
}
