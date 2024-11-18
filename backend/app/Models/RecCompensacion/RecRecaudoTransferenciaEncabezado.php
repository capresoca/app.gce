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
 * Class RecRecaudoTransferenciaEncabezado
 * @package App\Models\RecCompensacion
 */

class RecRecaudoTransferenciaEncabezado extends Model
{
    use PimpableTrait;

    protected $primaryKey = 'consecutivo_encabezado';
    protected $fillable = [
        'eps_transfiere',
        'eps_recibe',
        'fecha_reporte',
        'tipo_archivo',
        'fecha_descarga',
        'resultado',
        'fecha_cargue',
        'usuario_grabado',
        'fecha_grabado'
    ];
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint){
                $query->where('tipo_archivo', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
}
