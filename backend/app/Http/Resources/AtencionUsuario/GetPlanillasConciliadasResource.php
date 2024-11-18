<?php

namespace App\Http\Resources\AtencionUsuario;

use Illuminate\Http\Resources\Json\JsonResource;

class GetPlanillasConciliadasResource extends JsonResource
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
            'numero_planilla' => $this->numero_planilla,
            'tipo_cotizante' => $this->tipo_cotizante,
            'subtipo_cotizante' => $this->subtipo_cotizante,
            'fecha_pago' => $this->fecha_pago,
            'periodo_pago' => $this->periodo_pago,
            
            'dias_cotizados' => $this->dias_cotizados,
            'ingreso_base_cotizacion' => $this->ingreso_base_cotizacion,
            'sw_conciliacion' => $this->sw_conciliacion,
            'sw_vst' => $this->sw_vst,
            'numero_planilla' => $this->numero_planilla,
            
            'yy' => $this->soporte,
            'yy' => $this->soporte,
            
            'sw_ing' => $this->sw_ing,
            'sw_ret' => $this->sw_ret,
            'sw_sln' => $this->sw_sln,
            'sw_vsp' => $this->sw_vsp,
        ];
    }
}
