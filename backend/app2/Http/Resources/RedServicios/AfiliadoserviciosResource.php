<?php

namespace App\Http\Resources\RedServicios;

use Illuminate\Http\Resources\Json\JsonResource;

class AfiliadoserviciosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'rs_servcontratado_id' => $this->rs_servcontratado_id,
            'as_afiliado_id' => $this->as_afiliado_id,
            'rs_portabilidad_id' => $this->rs_portabilidad_id,
            'rs_servportabilidad_id' => $this->rs_servportabilidad_id,
            'rs_servhabilitado_id' => $this->rs_servhabilitado_id,
            'rs_asignamasivo_id' => $this->rs_asignamasivo_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'mini_afiliado' => $this->afiliado->mini_afiliado,
            'servicio_habilitado' => $this->servicio_habilitado,
            'asigna_masivo' => [
                'id' => $this->asigna_masivo->id,
                'gs_usuario_id' => $this->asigna_masivo->gs_usuario_id,
                'observacion' => $this->asigna_masivo->observacion,
                'created_at' => $this->asigna_masivo->created_at,
                'updated_at' => $this->asigna_masivo->updated_at,
                'usuario' => $this->asigna_masivo->usuario
            ]
        ];

    }
}
