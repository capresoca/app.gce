<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Refcobertura extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'refcobertura';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
}
