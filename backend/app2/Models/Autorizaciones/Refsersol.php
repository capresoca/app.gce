<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Refsersol extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'refsersol';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
}
