<?php

namespace App\Http\Resources\CuentasMedicas;

use Illuminate\Http\Resources\Json\JsonResource;

class FacturaServiciosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'consecutivo' => $this->consecutivo,
            'fecha' => $this->fecha,
            'plan_beneficio' => $this->plan_beneficio,
            'valor_compartido' => $this->valor_compartido,
            'valor_comision' => $this->valor_comision,
            'valor_descuentos' => $this->valor_descuentos,
            'valor_neto' => $this->valor_neto,
            'cm_radicado_id' => $this->cm_radicado_id,
            'estado' => $this->estado,
            'instancia' => $this->instancia,
            'revision_finalizada' => $this->revision_finalizada,
            'gs_usuario_avala_id' => $this->gs_usuario_avala_id,
            'radicado' => [
                'id' => $this->radicado->id,
                'consecutivo' => $this->radicado->consecutivo,
                'fecha' => $this->radicado->fecha,
                'periodo_inicio' => $this->radicado->periodo_inicio,
                'periodo_fin' => $this->radicado->periodo_fin,
                'radicado_entidad' => $this->radicado->radicado_entidad,
                'fecha_radicado' => $this->radicado->fecha_radicado,
                'estado' => $this->radicado->estado,
                'rs_contrato_id' => $this->radicado->rs_contrato_id,
                'observaciones' => $this->radicado->observaciones,
                'rip_radicado' => $this->radicado->rip_radicado,
                'entidad' => $this->radicado->entidad,
                'usuario' => [
                    'id' => $this->radicado->usuario['id'],
                    'identification' => $this->radicado->usuario['identification'],
                    'name' => $this->radicado->usuario['name'],
                ],
                'contrato' => $this->radicado->contrato,
            ],
            'entidad' => $this->entidad,
            'servicios' => $this->servicios,
            'servcapitas' => $this->servcapitas,
            'servicios_contratados' => $this->serv_contratados->get(),
            'glosas' => $this->glosas,
            'modalidad' => $this->modalidad,
        ];

        return $resource;
        //return parent::toArray($request);
    }
}
