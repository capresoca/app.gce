<?php

namespace App\Compensacion;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CpPlanilla extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    protected $fillable = [
        'aportante_id',
        'numero',
        'periodo',
        'registros',
        'gs_usuario_id'
    ];

    public function usuario () {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }
}


