<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class RsAfiservicambio extends Model implements  Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'rs_afiservicambios';

    protected $fillable = ['as_afiliado_id','id_serv_anterior','id_portabilidad','gs_usuario_id'];
}
