<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Refqx extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'refqx';
    protected $primaryKey = 'codigo';
    public $incrementing = false;

}
