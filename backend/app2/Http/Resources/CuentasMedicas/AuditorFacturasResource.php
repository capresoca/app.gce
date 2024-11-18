<?php

namespace App\Http\Resources\CuentasMedicas;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditorFacturasResource extends JsonResource
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
            'plan_beneficio' => $this->plan_beneficio !== null ? $this->plan_beneficio : 'N/A',
            'entidad' => $this->entidad,
            'estado' => $this->estado,
            'no_factura' => $this->consecutivo,
            'consecutivo_radicado' => isset($this->radicado) ? $this->radicado['consecutivo_radicado'] : 'N/A',
            'valor_neto' => $this->valor_neto,
            'auditores' => isset($this->auditores) ? $this->auditores : []
        ];
    }
}
