<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsClasecotizante extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;
}
