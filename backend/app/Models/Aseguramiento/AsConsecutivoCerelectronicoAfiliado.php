<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsConsecutivoCerelectronicoAfiliado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['consecutivo_electronico','as_afiliado_id'];
}
