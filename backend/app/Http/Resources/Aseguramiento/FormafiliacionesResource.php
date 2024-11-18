<?php

namespace App\Http\Resources\Aseguramiento;

use App\Models\General\GnMunicipio;
use Illuminate\Http\Resources\Json\JsonResource;

class FormafiliacionesResource extends JsonResource
{

    public function toArray($request)
    {
        $ubicacion  = $this->ubicacion($this->afiliado->gn_municipio_id);

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
            'listado_beneficiarios' => $this->beneficiarios,
            'ubicacion' => $ubicacion,
            'status'    => 'ok'
        ];

    }

    public function ubicacion ($idMunicipio)
    {
        $municipio = GnMunicipio::where('id','=',$idMunicipio)->get();
        $ubicacion = $municipio[0]->nombre. ', ' . $municipio[0]->departamento->nombre;
        return $ubicacion;
    }

}
