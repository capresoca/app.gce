<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AuAnexot3Entrega extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
}
