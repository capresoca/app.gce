<?php

namespace App\Compensacion;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class CpAportesACH extends Model
{
    use PimpableTrait;

    protected $table = 'cp_piladetalles';

    protected $searchable = [
        'tipo_documento_cotizante',
        'identificacion_cotizante',

    ];

    public function aportante(){
        return $this->belongsTo(CpPilaACH::class,'cp_pila_id');
    }


}
