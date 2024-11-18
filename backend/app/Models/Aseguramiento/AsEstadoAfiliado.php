<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AsEstadoafiliado extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;
}
