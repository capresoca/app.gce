<?php

namespace App\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Reforigenautorizacion extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'reforigenautorizacion';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
}
