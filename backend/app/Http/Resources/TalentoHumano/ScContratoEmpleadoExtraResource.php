<?php

namespace App\Http\Resources\TalentoHumano;

use Illuminate\Http\Resources\Json\JsonResource;

class ScContratoEmpleadoExtraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     **
     * @author Jorge Hernández 01/05/2020
     * Creando Soluciones Informaticas Ltda
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $nombre = null;
        switch ($this->causacion) {
            case 1: $nombre = 'Quincenal'; break;
            case 2: $nombre = 'Mensual'; break;
            case 3: $nombre = 'Bimensual'; break;
            case 4: $nombre = 'Trimensual'; break;
            case 5: $nombre = 'Semestral'; break;
            case 6: $nombre = 'Anual'; break;
        }

        $resources = parent::toArray($request);
        $resources['nombre_causacion'] = $nombre;
        return $resources;
    }
}
