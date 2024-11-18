<?php

namespace App\Http\Resources\RedServicios;

use Illuminate\Http\Resources\Json\JsonResource;

class PortabilidadResource extends JsonResource
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
            'consecutivo' => $this->consecutivo,
            'afiliado' => !$this->afiliado ? null : [
                'mini_afiliado' => $this->afiliado->mini_afiliado
            ],
            'municipio_origen' => !$this->afiliado ? null : $this->afiliado->municipio['nombre'],
            'municipio_destino' => $this->municipio_receptor['nombre'],
            'fecha_solicitud' => $this->fecha_solicitud,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_afiliado' => !$this->afiliado ? null : $this->afiliado->id,
            'estado' => $this->estado,
            'name' => $this->usuario['name'],
            'email' => $this->usuario['email'],
            'cancelacion' => $this->cancelPortabilidad
            //'cancelacion' => $this->cancelacion ?? null
//            'afiliado' => !$this->afiliado ? null : $this->afiliado->emoticon . ' ' . $this->afiliado->nombre_completo,
        ];
    }
}
