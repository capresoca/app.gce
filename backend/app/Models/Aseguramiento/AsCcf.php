<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsCcf extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['cod_habilitacion', 'nombre', 'estado'];
    protected $hidden = ['created_at','updated_at'];
}
