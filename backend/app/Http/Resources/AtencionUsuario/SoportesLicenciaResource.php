<?php

namespace App\Http\Resources\AtencionUsuario;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\LiGestiones\LiLicenciaSoporte;

class SoportesLicenciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
        $urlSoportes =  LiLicenciaSoporte::where('consecutivo_licencia',$this->consecutivo_licencia)->get();
        return [
            'descripcion' => $this->descripcion,
            'urls' => $urlSoportes,
            'obligatorio' => $this->sw_obligatorio == 1 ? 'Si' : 'No',
            'observacion' => $this->observacion,
            'consecutivo_licencia' => $this->consecutivo_licencia
        ];
    }
}
