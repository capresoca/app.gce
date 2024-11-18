<?php

namespace App\Compensacion;

use App\Models\Aseguramiento\AsAfiliadoPagador;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CpLiquidacione extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'relacion_laboral_id',
        'periodo',
        'ibc',
        'dias_pagados',
        'retiro',
        'ingreso'
    ];

    public function aportes(){
        return $this->hasMany(CpAporte::class, 'liquidacion_id');
    }

    public function relacionLaboral(){
        return $this->belongsTo(AsAfiliadoPagador::class, 'relacion_laboral_id');
    }
}
