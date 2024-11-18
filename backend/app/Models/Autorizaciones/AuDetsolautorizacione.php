<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AuDetsolautorizacione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'au_solautorizacion_id',
        'codigo',
        'nombre',
        'tipo',
        'cantidad',
        'valor_unitario',
        'valor_afiliado',
        'valor_eps',
        'pbs',
        'aprobado',
        'observaciones'
    ];
    protected $hidden = ['created_at','updated_at'];
}
