<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class AfiliadosPagadorResource extends JsonResource
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
            'identificacion_afiliado' => $this->afiliado->identificacion_completa,
            'nombre_afiliado' => $this->afiliado->emoticon.$this->afiliado->nombre_completo,
            'id_afiliado' => $this->afiliado->id,
            'identificacion_aportante' => $this->pagador->identificacion,
            'nombre_aportante' => $this->pagador->razon_social,
            'fecha_vinculacion' => $this->fecha_vinculacion,
            'ibc' => $this->ibc,
            'estado' => $this->estado,
            'periodos_mora' => $this->periodos_mora,
            'mini_afiliado' => $this->afiliado ? $this->afiliado->mini_afiliado : null
        ];
    }
}
