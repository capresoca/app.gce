<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

/**
 * @author Jorge Eduardo Hernández Oropeza 12/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoPlanillaEncabezado
 * @package App\Models\RecCompensacion
 */

class RecRecaudoPlanillaEncabezado extends Model
{
    use PimpableTrait;

    protected $table = 'rec_recaudo_planilla_encabezados';
    protected $primaryKey = 'consecutivo_recaudo';

    protected $searchable = ['search'];

    protected $fillable = [
        'fecha_pago',
        'operador',
        'numero_planilla',
        'tipo_archivo',
        'fecha_descarga',
        'resultado',
        'usuario_grabado',
        'fecha_grabado',
        'fecha_cargue',
        'tipo_documento',
        'numero_documento',
        'periodo_pago',
        'consecutivo_periodo_liquidacion',
        'consecutivo_encabezado_ac',
        'codigo_eps_transfiere'
    ];

    public function recaudo_transferencia_encabezado () {
        return $this->belongsTo(RecRecaudoTransferenciaEncabezado::class,'consecutivo_encabezado_ac','consecutivo_encabezado');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('resultado', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

}
