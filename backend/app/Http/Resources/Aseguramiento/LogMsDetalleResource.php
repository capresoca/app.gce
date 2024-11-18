<?php

namespace App\Http\Resources\Aseguramiento;

use App\Models\Enums\EEstadoProcesado;
use Illuminate\Http\Resources\Json\JsonResource;

class LogMsDetalleResource extends JsonResource
{
    
    public function toArray($request)
    {
        // $vigencia   = $this->ubicacion($this->consecutivo_vigencia); // presenta error
        return [
            'id' => $this->id,
            'descripcion' => $this->descripcion,
            'vigencia' => $this->vigencia,
            'fecha' => $this->fecha,
            'informacion' => $this->informacion,
            'estado_procesado' => empty($this->estado_procesado) ? 'No' : (EEstadoProcesado::getResultado($this->estado_procesado)->getDescripcion())
            // 'mensaje_error'         => 'EL AFILIADO TIENE NOVEDADES PENDIENTES EN EL PERIODO '.mb_strtoupper($vigencia==null ? '' : $vigencia->descripcion)
        ];
    }
    
}