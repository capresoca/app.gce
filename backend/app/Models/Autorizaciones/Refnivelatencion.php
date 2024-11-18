<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Refnivelatencion extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'refnivelatencion';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $hidden = ['created_at','updated_at'];
}
