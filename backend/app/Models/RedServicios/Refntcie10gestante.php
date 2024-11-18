<?php

namespace App\Models\RedServicios;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Refntcie10gestante extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'refntcie10gestantes';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $hidden = ['created_at','updated_at'];
}
