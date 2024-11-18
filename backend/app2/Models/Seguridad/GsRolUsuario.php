<?php

namespace App\Seguridad;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GsRolUsuario extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];
}
