<?php

namespace App\Http\Resources\Autorizaciones;

use Illuminate\Http\Resources\Json\JsonResource;

class AutsolicitudResource extends JsonResource
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
            'id'             => $this->id,
            'fecha_solicitud'=> $this->fecha,
            'nombre_afiliado'=> $this->autorizacion->afiliado->nombre_completo,
            'identificacion' => $this->autorizacion->afiliado->identificacion_completa,
            'origen'         => $this->autorizacion->entidad_origen->nombre,
            'servicio'       => $this->autorizacion->servicio->servicio->nombre,
            'destino'        => $this->autorizacion->contrato->entidad->nombre,
            'estado'         => $this->autorizacion->estado
        ];
    }
}
