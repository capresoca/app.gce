<?php

namespace App\Http\Resources\AtencionUsuario;

use Illuminate\Http\Resources\Json\JsonResource;

class TbDiasFestivosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $nuevaFecha = date("d/m/Y", strtotime($this->fecha));
        // $fechaComoEntero = strtotime($nuevaFecha);
        // $ano = date("Y", $fechaComoEntero);
        return [
            'descripcion' => $this->descripcion,
            'dia_festivo' => $this->dia_festivo,
            'fecha' => $nuevaFecha,
            'fecharaw' => $this->fecha
        ];
    }
}
