<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsAfp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable= ['nombre', 'codigo'];
    protected $hidden = ['created_at','updated_at'];
}
