<?php

namespace App\Models\Niif;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class NfRetencione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'nf_retenciones';

    protected $fillable = [
        'cr_cuentasxcobrar_id',
        'nf_comdiario_id',
        'pg_cxp_id',
        'porcentaje',
        'valor_base',
        'valor_retencion'
    ];
}
