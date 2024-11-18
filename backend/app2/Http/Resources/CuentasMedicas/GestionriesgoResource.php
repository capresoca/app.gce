<?php

namespace App\Http\Resources\CuentasMedicas;

use Illuminate\Http\Resources\Json\JsonResource;

class GestionriesgoResource extends JsonResource
{


    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request)
        );
    }
}
