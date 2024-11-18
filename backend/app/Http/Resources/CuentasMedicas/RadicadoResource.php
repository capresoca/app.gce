<?php

namespace App\Http\Resources\CuentasMedicas;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RadicadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'avance_proceso' => $this->avance_proceso,
            'radiccado_rip' => $this->cod_radicacion_ct,
            'consecutivo_radicado' => $this->consecutivo_radicado,
            'entidad' => isset($this->entidad) ? [
                'id' => $this->entidad['id'],
                'nombre' => $this->entidad['nombre']
            ] : null,
            'estado' => $this->estado,
            'nombre' => $this->nombre,
            'fecha_radicado' => isset($this->ripRadicado)
                ? Carbon::parse($this->ripRadicado['fecha_radicado'])->format('Y-m-d')
                : (null),
            'usuario' => $this->usuario,
            'estadolote' => isset($this->estadolote)
                ? [
                    'id' => $this->estadolote['id'],
                    'estado' => $this->estadolote['estado'],
                    'nombre_estado' => $this->nombre_estado,
                    'cm_radicado_id' => $this->estadolote['cm_radicado_id'],
                    'descripcion' => $this->estadolote['descripcion'],
                    'aceptado' => $this->estadolote['aceptado'],
                    'dias' => $this->estadolote['dias'],
                ] : null,
            'tipo_facturacion' => $this->ripRadicado['tipo_facturacion'],
            'enviar_glosas' => $this->enviar_glosas,
            'total_glosas' => $this->total_glosas
        ];
    }
}
