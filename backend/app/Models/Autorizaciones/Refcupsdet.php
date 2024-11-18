<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Refcupsdet extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'refcupsdet';
    protected $primaryKey = 'codigo';
    public $incrementing = false;

}
