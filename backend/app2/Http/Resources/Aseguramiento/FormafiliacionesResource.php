<?php

namespace App\Http\Resources\Aseguramiento;

use Illuminate\Http\Resources\Json\JsonResource;

class FormafiliacionesResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'afiliado_id' => $this->afiliado->id,
            'emoticon' => $this->afiliado->emoticon,
            'mini_afiliado' => $this->afiliado ? $this->afiliado->mini_afiliado : null,
            'beneficiarios' => count($this->beneficiarios),
            'estado' => $this->estado,
            'identificacion' => $this->afiliado->identificacion_completa,
            'recien_nacido' => $this->recien_nacido,
            'fecha_radicacion' => $this->fecha_radicacion,
            'puntaje_sisben' => $this->puntaje_sisben,
            'listado_beneficiarios' => $this->beneficiarios
        ];

    }
}
