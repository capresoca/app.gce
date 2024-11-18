<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Refmotivoneg extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $primaryKey = 'codigo';
    protected $table = 'refmotivoneg';
    public $incrementing = false;
}
