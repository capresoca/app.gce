<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;

class AsBduaregrespuesta extends Model
{
    protected $fillable = [
        'as_bduarespuesta_id',
        'as_tramite_id',
        'respuesta'
    ];

    public function glosas(){
        return $this->hasMany(AsBduaglosa::class, 'as_bduaregrespuesta_id');
    }

    public function tramite(){
        return $this->belongsTo(AsTramite::class, 'as_tramite_id');
    }

    public function nuevo_tramite(){
        return $this->belongsTo(AsTramite::class, 'nuevo_tramite_id');
    }

    public function acciones(){
        return $this->hasMany(AsBduaaccione::class, 'as_bduaregrespuesta_id');
    }

    public function respuesta(){
        return $this->belongsTo(AsBduarespuesta::class, 'as_bduarespuesta_id');
    }
}
