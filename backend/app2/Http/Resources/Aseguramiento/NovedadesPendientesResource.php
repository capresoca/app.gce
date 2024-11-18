<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class NovedadesPendientesResource extends JsonResource
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
            'nombre' => $this->novedad->afiliado->nombre_completo,
            'identificacion' => $this->novedad->afiliado->identificacion_completa,
            'fecha_nacimiento' => $this->novedad->afiliado->fecha_nacimiento,
            'fecha_afiliacion' => $this->novedad->afiliado->fecha_afiliacion,
            'fecha_ini_novedad' => $this->fecha_ini_novedad,
            'codigo_novedad' => $this->novedad->novedad->codigo,
            'nombre_novedad' => $this->novedad->novedad->descripcion,
            'nuevo_valor_1' => $this->novedad->v1,
            'nuevo_valor_2' => $this->novedad->v2,
            'nuevo_valor_3' => $this->novedad->v3,
            'nuevo_valor_4' => $this->novedad->v4,
            'nuevo_valor_5' => $this->novedad->v5,
            'nuevo_valor_6' => $this->novedad->v6,
            'nuevo_valor_7' => $this->novedad->v7,

        ];
    }
}
