<?php

namespace App\Models\Tesoreria;

use Illuminate\Database\Eloquent\Model;

class TsCompegresoConcepto extends Model
{
    protected $fillable = ['ts_compegreso_id', 'ts_concepto_egreso_id'];

    public function detalles()
    {
        return $this->hasMany(TsConceptoegresoDetalle::class,'ts_compegreso_concepto_id');
    }

    public function concepto()
    {
        return $this->belongsTo(TsConceptoEgreso::class,'ts_concepto_egreso_id');
    }

    public function getValorAttribute()
    {
        return $this->detalles->sum('valor');
    }
}
