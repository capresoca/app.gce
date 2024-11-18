<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class AsBduaarchivo extends Model
{
    protected $fillable = [
        'as_bduaproceso_id' ,
        'nombre' ,
        'as_tipbduaarchivo_id',
        'numero_registros'
    ];

    protected $appends = ['total_tramites','url_signed'];

    public function tipo(){
        return $this->belongsTo(AsTipbduaarchivo::class, 'as_tipbduaarchivo_id');
    }

    public function tramites(){
        return $this->hasMany(AsTramite::class, 'as_bduaarchivo_id');
    }

    public function respuestas(){
        return $this->hasMany(AsBduarespuesta::class, 'as_bduaarchivo_id');
    }

    public function getTotalTramitesAttribute()
    {
        return $this->tramites()->count();
    }

    public function proceso()
    {
        return $this->belongsTo(AsBduaproceso::class, 'as_bduaproceso_id');
    }

    public function getUrlSignedAttribute(){
        return URL::temporarySignedRoute('bdua_archivo',now()->addMinute(30),[$this->id]);
    }

}


