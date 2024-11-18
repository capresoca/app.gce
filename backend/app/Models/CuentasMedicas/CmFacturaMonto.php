<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmFacturaMonto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'cm_factura_montos';
}
