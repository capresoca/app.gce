<?php

namespace App\Http\Resources\RedServicios;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsignamasivoResource extends JsonResource
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
            'gs_usuario_id' => $this->gs_usuario_id,
            'observacion' => $this->observacion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'usuario' => $this->usuario,
            'tipo_proceso' => $this->tipo_proceso,
            'afiliados_con_servicios' => count($this->afiliados),
            'afiliados' => $this->afiliados,
            'servicio_anterior' => $this->servicio_anterior,
            'servicios_asignados' => $this->servicios_asignados
        ];

    }
}
