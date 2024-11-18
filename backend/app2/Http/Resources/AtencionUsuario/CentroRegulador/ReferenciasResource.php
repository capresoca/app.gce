<?php

namespace App\Http\Resources\AtencionUsuario\CentroRegulador;

use App\Traits\EmoticonEdadTrait;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferenciasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        $fechaNacimiento = $this->afiliado->fecha_nacimiento;
//        $sexo = $this->afiliado->sexo->nombre;
        $resource = [
            'id' => $this->id,
            'consecutivo' => $this->consecutivo,
            'fecha_solicitud' => $this->fecha_solicitud,
            'identificacion_afiliado' => $this->afiliado ? $this->afiliado->identificacion_completa : null,
            'nombre_completo' => $this->afiliado ? $this->afiliado->nombre_completo : null,
            'origen' => $this->entidadUno ? $this->entidadUno->nombre : null,
            'estado' => $this->estado,
            'id_afiliado' => $this->afiliado ? $this->afiliado->id : null,
//            EmoticonEdadTrait::IdEmoticonsByEdad($this->afiliado) . ' ' . 'carita_nombre_afiliado' => $this->afiliado ? $this->emoticonsAfiliado($fechaNacimiento, $sexo) . ' ' . $this->afiliado->nombre_completo : null,
            'carita_nombre_afiliado' => $this->afiliado ? $this->afiliado->emoticon . '-' . $this->afiliado->nombre_completo : null,
            'bitacota' => ($this->bitacoras->count()) ? true : false,
            'afiliado' => $this->afiliado ? $this->afiliado : null,
            'usuario' => $this->usuario->name
        ];
        return $resource;
    }

//    public function emoticonsAfiliado ($fecha, $sexo) {
//        $edad = Carbon::parse($fecha)->age;
//        $emoticons = [' 👶 ',' 🧒 ',' 👦 ',' 👧 ',' 👨 ',' 👩 ',' 👵 ',' 👴 '];
//        if ($edad <= 5) {
//            return $emoticons[0];
//        }
//        if ($edad <= 12) {
//            return $emoticons[1];
//        }
//        if ($edad <= 17) {
//            return $sexo === 'Masculino' ? $emoticons[2] : $emoticons[3];
//        }
//        if ($edad <= 64) {
//            return $sexo === 'Masculino' ? $emoticons[4] : $emoticons[5];
//        }
//        return $sexo === 'Masculino' ? $emoticons[7] : $emoticons[6];
//
//    }
}
