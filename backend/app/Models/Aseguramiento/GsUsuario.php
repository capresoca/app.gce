<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GsUsuario extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['identification', 'name'];
    protected $hidden = ['created_at','updated_at'];
}
