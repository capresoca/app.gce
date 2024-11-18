<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class AsTramitesResource extends JsonResource
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
            'afiliado' => $this->afiliado,
            'pagador' => $this->pagador,
            'estado_tramite' => $this->estado,
            'tipo_tramite' => $this->tipo_tramite
        ];
    }
}
