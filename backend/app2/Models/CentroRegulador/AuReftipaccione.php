<?php

namespace App\Models\CentroRegulador;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AuReftipaccione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'accion', 'metodo', 'color', 'icon', 'option'
    ];
}
