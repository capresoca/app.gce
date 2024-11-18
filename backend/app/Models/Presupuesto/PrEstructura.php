<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;

class PrEstructura extends Model
{
    public function conceptos()
    {
        return $this->hasMany(PrConcepto::class, 'pr_estructura_id')
                    ->whereHas('nivel',function ($query){
                        $query->where('nivel',1);
                    });
    }
}
