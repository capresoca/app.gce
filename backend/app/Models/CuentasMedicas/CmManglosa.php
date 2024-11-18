<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmManglosa extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
