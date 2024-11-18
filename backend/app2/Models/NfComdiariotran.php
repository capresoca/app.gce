<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class NfComdiariotran extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'nf_comdiariotrans';

    protected $fillable = [
        'nf_comdiario_id',
        'cm_radicado_id',
        'pg_cxp_id',
        'pr_obligacion_id'
    ];
}
