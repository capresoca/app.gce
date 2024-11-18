<?php

namespace App\Http\Resources\AuditoriaConcurrente;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ConcurrenciaResource extends JsonResource
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
            'afiliado' => isset($this->afiliado) ? $this->afiliado : (null .'E'),
            'tiempo_estancia' => $this->tiempo_estancia,
            'consecutivo_ips' => $this->consecutivo_ips,
            'entidad' => isset($this->entidad) ? $this->entidad : (null .'E'),
            'fecha' => Carbon::parse($this->fecha)->format('Y-m-d'),
            'color_semaforo' => $this->color_semaforo,
            'estado' => $this->estado,
            'consecutivo' => $this->consecutivo
        ];
    }
}
