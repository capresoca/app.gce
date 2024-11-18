<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AsMsauditoria extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $hidden = ['created_at','updated_at'];
    protected $fillable = [
        'as_msubsidiado_id',
        'accion',
        'id_registro',
        'detalle',
        'ejecutada'
    ];
    protected $searchable = [];

}
