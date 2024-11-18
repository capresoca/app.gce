<?php

namespace App\Http\Resources\Tesoreria;

use Illuminate\Http\Resources\Json\JsonResource;

class CompegresoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'consecutivo' => $this->id,
            'fecha' => $this->fecha,
            'tercero' => [
                'nombre_completo' => $this->tercero->nombre_completo,
                'razon_social' => $this->tercero->razon_social,
                'identificacion_completa' => $this->tercero->identificacion_completa
            ],
            'cencosto' => [
                'codigo' => $this->cencosto->codigo,
                'nombre' => $this->cencosto->nombre,
                'tipo' => $this->cencosto->tipo
            ],
            'estado' => $this->estado,
            'descripcion' => $this->descripcion ?? 'N/A'
        ];
    }
}
