<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmEstadosrad extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;
}
