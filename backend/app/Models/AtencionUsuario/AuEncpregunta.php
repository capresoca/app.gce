<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AuEncpregunta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
