<?php

namespace App\Models\Tesoreria;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TsRecajapago extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];
}
