<?php

namespace App\Http\Resources\GestionSeguridad;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        return [
            'fecha' => $this->created_at->toDateTimeString(),
            'ip' => $this->ip_address,
            'evento' => $this->event,
            'modelo' => $this->modelo->nombre,
            'id' => $this->auditable_id,
            'usuario' => $this->usuario->name.' ('.$this->usuario->email.')',
            'datos_anteriores' => $this->old_values,
            'datos_nuevos' => $this->new_values,
        ];
    }
}
