<?php

namespace App\Http\Resources\RedServicios;

use Illuminate\Http\Resources\Json\JsonResource;

class AsignamasivosResource extends JsonResource
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
            'gs_usuario_id' => $this->gs_usuario_id,
            'observacion' => $this->observacion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'usuario' => [
                'name' => $this->name,
                'email' => $this->email
            ],
            'tipo_proceso' => $this->tipo_proceso,
            'afiliados_con_servicios' => count($this->afiliados),
            'servicios_asignados' => $this->total_services,
            'services_tot' => count($this->total_services)
        ];
    }
}
