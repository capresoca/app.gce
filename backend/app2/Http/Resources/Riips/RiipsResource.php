<?php

namespace App\Http\Resources\Riips;

use Illuminate\Http\Resources\Json\JsonResource;

class RiipsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //$resources = [
        //    'id' => $this->id,
        //    'tipo_facturacion' => $this->tipo_facturacion,
        //    'rs_entidad_id' => $this->rs_entidad_id,
        //    'estado_radicacion' => $this->estado_radicacion,
        //    'cod_radicacion_ct' => $this->cod_radicacion_ct,
        //    'cod_radicacion' => $this->cod_radicacion,
        //    'rs_entidad' => [
        //        'id' => $this->rs_entidad['id'],
        //        'cod_habilitacion' => $this->rs_entidad['cod_habilitacion'],
        //        'nombre' => $this->rs_entidad['nombre'],
        //        'tipo' => $this->rs_entidad['tipo'],
        //    ]
        //];
        //
        //return $resources;
        return parent::toArray($request);
    }
}
