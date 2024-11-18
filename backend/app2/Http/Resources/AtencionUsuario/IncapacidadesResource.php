<?php

namespace App\Http\Resources\AtencionUsuario;

use Illuminate\Http\Resources\Json\JsonResource;

class IncapacidadesResource extends JsonResource
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
            'tipo' => $this->tipo_incapacidad->prestacion->tipo. ' - ' . $this->tipo_incapacidad->tipo,
            'afiliado' => !$this->afiliado ? null : $this->afiliado->emoticon.$this->afiliado->nombre_completo,
            'identificacion_afiliado' => !$this->afiliado ? null : $this->afiliado->identificacion_completa,
            'aportante' => !$this->relacion_laboral || !$this->relacion_laboral->pagador ? null : $this->relacion_laboral->pagador->razon_social,
            'identificacion_aportante' => !$this->relacion_laboral || !$this->relacion_laboral->pagador ? null : $this->relacion_laboral->pagador->identificacion,
            'fecha' => $this->fecha,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'dias_incapacidad_total' => $this->dias_incapacidad_total,
            'estado' => $this->estado,
            'consecutivo' => $this->consecutivo,
            'numeroConsecutivo' =>  $this->numeroDocumento,
            'pr_solicitud_cp_id' => $this->pr_solicitud_cp_id,
            'mini_afiliado' => $this->afiliado ? $this->afiliado->mini_afiliado : null
        ];
    }
}
