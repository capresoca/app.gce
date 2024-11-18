<?php

namespace App\Http\Resources\RedServicios;

use Illuminate\Http\Resources\Json\JsonResource;

class MinutaResource extends JsonResource
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
            'ce_procontractuale_id' => $this->ce_procontractuale_id,
            'objeto' => $this->objeto,
            'entidad' => !$this->entidad ? null : $this->entidad->nombre,
            'estado' => $this->estado,
            'plazo_meses' => $this->plazo_meses,
            'plazo_dias' => $this->plazo_dias,
            'fecha_contrato' => $this->fecha_contrato,
            'valor' => $this->valor,
            'numero_contrato' => $this->numero_contrato,
            'fecha_finalizacion' => $this->fecha_finalizacion,
            'modificaciones_plazo' => $this->modificaciones_plazo,
            'modificaciones_valor' => $this->modificaciones_valor,
            'fecha_acta_inicio' => $this->fecha_acta_inicio,
            'valor_total' => $this->valor_total,
            'proceso_contractual' => $this->proceso_contractual
        ];
    }
}
