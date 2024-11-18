<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Refviasol extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'refviasol';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
}
