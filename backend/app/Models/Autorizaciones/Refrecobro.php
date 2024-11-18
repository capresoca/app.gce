<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Refrecobro extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'refrecobro';
    protected $primaryKey = 'codigo';

}
