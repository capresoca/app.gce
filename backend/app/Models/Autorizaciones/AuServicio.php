<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AuServicio extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
}
