<?php

namespace App\Http\Resources\CuentasMedicas;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FacturasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'plan_beneficio' => $this->plan_beneficio !== null ? $this->plan_beneficio : 'N/A',
            'entidad' => [
                'cod_habilitacion' => $this->entidad['cod_habilitacion'],
                'nombre' => $this->entidad['nombre'],
                'tipo' => $this->entidad['tipo'],
            ],
            'estado' => $this->estado,
            'no_factura' => $this->consecutivo,
            'consecutivo_radicado' => isset($this->radicado) ? $this->radicado['consecutivo_radicado'] : 'N/A',
            'valor_neto' => $this->valor_neto,
            'auditores' => isset($this->auditores) ? $this->auditores : [],
            'tipo_facturacion' => $this->modalidad,
            'feha_repcapita' => !is_null($this->feha_repcapita) ? Carbon::parse($this->feha_repcapita)->format('Y-m-d') : (null)
        ];
    }
}
