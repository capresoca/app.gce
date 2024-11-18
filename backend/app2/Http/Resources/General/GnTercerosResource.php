<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Resources\Json\JsonResource;

class GnTercerosResource extends JsonResource
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
            'nombre_completo' => $this->nombre_completo,
            'identificacion' => $this->identificacion_completa,
            'digito_verificacion' => $this->digito_verificacion,
            'tipo_contribuyente' => $this->tipo_contribuyente,
            'celular' => $this->celular,
            'estado' => $this->estado,
            'correo_electronico' => $this->correo_electronico
        ];
    }
}
