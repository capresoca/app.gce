<?php

namespace App\Http\Resources\Pagos;

use Illuminate\Http\Resources\Json\JsonResource;

class PgAnticipoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $resource = [
            'id' => $this->id,
            'consecutivo' => str_pad($this->consecutivo,6,"0",STR_PAD_LEFT),
            'observacion' => $this->observacion,
            'fecha_documento' => $this->fecha_documento,
            'origen' => $this->origen,
            'valor_crudo' => $this->valor,
            'valor_formateado' => '$ '. number_format($this->valor, 2, ',', '.'),
            'tercero' => [
                'id' => $this->tercero ? $this->tercero->id : null,
                'nombre_completo' => $this->tercero ? $this->tercero->nombre_completo : null,
                'identificacion' => $this->tercero ? $this->tercero->identificacion_completa : null
            ],
            'cuenta' => [
                'id' => $this->cuenta ? $this->cuenta->id : null,
                'codigo_nombre' => $this->cuenta ? '<b>' . $this->cuenta->codigo .'</b> - ' . strtoupper($this->cuenta->nombre) : null
            ],
            'com_egreso' => [
                'id' => $this->comEgreso ? $this->comEgreso->id : null,
                'consecutivo' => $this->comEgreso ? str_pad($this->comEgreso->consecutivo,6,"0",STR_PAD_LEFT) : null
            ],
            'saldo_incial' => [
                'id' => $this->saldoIncial ? $this->saldoIncial->id : null,
                'consecutivo' => $this->saldoIncial ? str_pad($this->saldoIncial->consecutivo,6,"0",STR_PAD_LEFT) : null,
                'valor' => $this->saldoIncial->valor
            ]
        ];
        return $resource;
//        return parent::toArray($request);
    }
}
