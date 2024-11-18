<?php

namespace App\Http\Resources\TalentoHumano;

use Illuminate\Http\Resources\Json\JsonResource;

class ScLiquidacionNoEncabezadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $mesq = null;
        switch ($this->mes) {
            case 1: $mesq = 'Enero';break;
            case 2: $mesq = 'Febrero';break;
            case 3: $mesq = 'Marzo';break;
            case 4: $mesq = 'Abril';break;
            case 5: $mesq = 'Mayo';break;
            case 6: $mesq = 'Junio';break;
            case 7: $mesq = 'Julio';break;
            case 8: $mesq = 'Agosto';break;
            case 9: $mesq = 'Septiembre';break;
            case 10: $mesq = 'Octubre';break;
            case 11: $mesq = 'Noviembre';break;
            case 12: $mesq = 'Diciembre';break;

        }
        $estado = null;
        switch ($this->estado) {
            case 0: $estado = 'Anulada';break;
            case 1: $estado = 'Abierta';break;
            case 2: $estado = 'Liquidada';break;
            case 3: $estado = 'Pagada';break;
            case 4: $estado = 'Preliquidada';break;
        }

        $resources = parent::toArray($request);
        $resources['mes_nombre'] = $mesq;
        $resources['estado_nombre'] = $estado;
        return $resources;
    }
}
