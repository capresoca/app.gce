<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsArl extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['codigo', 'nombre'];
    protected $hidden = ['created_at','updated_at'];
}
