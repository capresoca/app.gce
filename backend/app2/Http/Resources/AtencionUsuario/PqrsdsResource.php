<?php

namespace App\Http\Resources\AtencionUsuario;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PqrsdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
        [
          'ano' => Carbon::createFromFormat('Y-m-d',$this->fecha_recepcion)->format('Y')
        ];
    }
}
