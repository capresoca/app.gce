<?php

namespace App\Models\Aseguramiento\Procesos;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsR2 extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
