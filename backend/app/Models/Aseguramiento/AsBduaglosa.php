<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;

class AsBduaglosa extends Model
{
    protected $fillable = [
        'as_bduaregrespuesta_id',
        'as_bduatipglosa_id',
        'glosa',
        'estado',
        'observaciones',
        'as_nuevotramite_id'
    ];

    public function tipo()
    {
        return $this->belongsTo(AsBduatipglosa::class, 'as_bduatipglosa_id');
    }
}







