<?php

namespace App\Models\OficinaJuridica;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class OjPretencione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
}
