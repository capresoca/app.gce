<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmFactsdevuelta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'cm_factsdevueltas';
    protected $fillable = [
        'id',
        'cm_radicado_id',
        'consecutivo',
        'fecha',
        'no_contrato',
        'plan_beneficio',
        'valor_compartido',
        'valor_comision',
        'valor_neto',
        'valor_descuentos',
        'estado',
        'instancia',
        'revision_finalizada',
        'gs_usuario_avala_id'
    ];
}
