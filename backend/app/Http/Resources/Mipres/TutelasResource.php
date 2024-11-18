<?php

namespace App\Http\Resources\Mipres;

use Illuminate\Http\Resources\Json\JsonResource;

class TutelasResource extends JsonResource
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
            'id'                => $this->id,
            'NoTutela'    => $this->NoTutela,
            'FTutela'     => $this->FTutela,
            'identificacion'    => $this->identificacion,
            'afiliado_id'       => $this->as_afiliado_id,
            'paciente'          => $this->paciente,
            'estadoPrescripcion'=> $this->estadoPrescripcion,
            'medicamentos'      => $this->medicamentos ? $this->medicamentos->count() : null,
            'procedimientos'    => $this->procedimientos ? $this->procedimientos->count() : null,
            'productosnutricionales'=> $this->nutricionales ? $this->nutricionales->count() : null,
            'serviciosComplementarios' => $this->complementarios ? $this->complementarios->count() : null,
            'estado'            => $this->estado,
            'direccionado'      => $this->direccionado(),
            'mini_afiliado' => $this->afiliado ? $this->afiliado->mini_afiliado : null
        ];
    }
}
