<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class AportantesPendientesResource extends JsonResource
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
            'identificacion' => $this->afiliacionPagador->tercero->identificacion_completa,
            'nombre' => $this->afiliacionPagador->tercero->nombre_completo,
            'tipo_aportante' => $this->afiliacionPagador->tipoAportante->nombre,
            'sector_aportante' => $this->afiliacionPagador->sector_aportante
        ];
    }
}
