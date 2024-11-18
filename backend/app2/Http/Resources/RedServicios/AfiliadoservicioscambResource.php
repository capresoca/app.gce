<?php

namespace App\Http\Resources\RedServicios;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AfiliadoservicioscambResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */

    public function toArray($request)
    {
        // return parent::toArray($request);

         return [
             'id' => $this->id,
             'identificacion' => $this->identificacion,
             'estado' => $this->estado,
             'gn_municipio_id' => $this->gn_municipio_id,
             'as_regimene_id' => $this->as_regimene_id,
             'fecha_nacimiento' => $this->fecha_nacimiento,
             'gn_sexo_id' => $this->gn_sexo_id,
             'fecha_afiliacion' => $this->fecha_afiliacion,
             'mini_afiliado' => $this->mini_afiliado,
             'estado_afiliado' => $this->estado_afiliado,
             'fecha_asignacion' => $this->fecha_asignacion,
             'rs_entidad_id' => $this->rs_entidad_id
        ];

    }
}
