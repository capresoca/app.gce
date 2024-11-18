<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class TrasladosSubsidiadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource =  [
            'consecutivo' => $this->consecutivo,
            'fecha_radicacion' => $this->fecha_radicacion,
            'estado' => $this->estado
        ];

        if($this->traslado){
            $resource['nombre'] = $this->traslado->afiliado->nombre_completo;
            $resource['identificacion'] = $this->traslado->afiliado->identificacion_completa;
            $resource['fecha_nacimiento'] = $this->traslado->afiliado->fecha_nacimiento;
        }

        return $resource;
    }
}
