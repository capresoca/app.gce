<?php

namespace App\Http\Resources\Aseguramiento;

use App\Models\Aseguramiento\AsAfiliado;
use Illuminate\Http\Resources\Json\JsonResource;
use function MongoDB\BSON\toJSON;

class AsAfiliadosResource extends JsonResource
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
            'id' => $this->id,
            'nombre_completo' => $this->nombre_completo,
            'emoticon' => $this->emoticon,
            'emoticon_nombre_completo' => $this->emoticon . '-' . $this->nombre_completo,
            'identificacion' => $this->identificacion_completa,
            'ficha_sisben' => $this->ficha_sisben,
            'puntaje_sisben' => $this->puntaje_sisben,
            'nivel_siben' => $this->nivel_sisben,
            'gn_tercero_id' => $this->gn_tercero_id,
            'estado' => $this->estado_afiliado->nombre,
            'regimen' => $this->regimen ? $this->regimen->codigo .' - '. $this->regimen->nombre : '',
            'mini_afiliado' => $this->mini_afiliado
        ];
    }
}
