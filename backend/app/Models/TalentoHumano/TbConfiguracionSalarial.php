<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

/**
 * @author Jorge Hernández 01/05/2020
 * Creando Soluciones Informaticas Ltda
 */

class TbConfiguracionSalarial extends Model
{
    use PimpableTrait;

    protected $table = 'tb_configuracion_salarial';
    protected $primaryKey = 'configuracion_salarial';
    protected $fillable = ['descripcion','sw_subsidio_transporte','porcentaje_salud','porcentaje_pension','porcentaje_solidaridad_pensional','porcentaje_arp','porcentaje_sena','porcentaje_caja','porcentaje_icbf'];
    public $timestamps = false;
    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('configuracion_salarial','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
