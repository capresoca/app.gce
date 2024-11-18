<?php

namespace App\Http\Resources\AuditoriaCuentas;

use Illuminate\Http\Resources\Json\JsonResource;

class RadicadosAsignadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'consecutivo_radicado' => $this->consecutivo_radicado,
            'auditores_asignados' => $this->auditoresAsignados,
            'facturas' => count($this->facturas),
            'codigo_habilitacion' => $this->entidad ? $this->entidad->cod_habilitacion : null,
            'nombre_entidad' => $this->entidad ? $this->entidad->nombre : null
        ];
        return $resource;
    }
}
