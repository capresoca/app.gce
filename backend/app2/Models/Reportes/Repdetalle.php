<?php

namespace App\Models\Reportes;

use Illuminate\Database\Eloquent\Model;

class Repdetalle extends Model
{
    protected $table = 're_repdetalles';

    protected $fillable = [
        're_reportes_id',
        'ref',
        'type',
        'label',
        'entidades'
    ];

//    public function getEntidadesAttribute($key)
//    {
//        $encodes = explode(',',trim($this->attributes['entidades']));
//
//        return $encodes;
//
//    }

}
