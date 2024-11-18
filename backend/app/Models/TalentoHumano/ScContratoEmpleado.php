<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @author Jorge Hernández 01/05/2020
 * Creando Soluciones Informaticas Ltda
 */

class ScContratoEmpleado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'sc_contrato_empleado';
    protected $primaryKey = 'contrato_empleado';
    protected $fillable = [
        'area',
        'base_prima',
        'base_vacaciones',
        'basico_actual',
        'cargo',
        'causal_despido',
        'centro_trabajo',
        'configuracion_salarial',
        'contrato_empleado',
        'dependencia',
        'dias_no_laborados',
        'empleado',
        'estado',
        'fecha_afiliacion_seguro',
        'fecha_final',
        'fecha_inicio',
        'fecha_liquidacion',
        'forma_pago',
        'indemnizacion_contra',
        'indemnizacion_favor',
        'jornada_laboral',
        'sub_tipo_cotizante',
        'sw_antiguo_regimen',
        'sw_salario_integral',
        'tipo_contrato',
        'tipo_cotizante',
        'tipo_liquidacion',
        'tipo_pago'
    ];
    public $timestamps = false;
    protected $searchable = ['search'];

    public function scempleado () {
        return $this->belongsTo(SCEmpleado::class,'empleado');
    }

    public function tbarea ()
    {
        return $this->belongsTo(TbArea::class,'area');
    }

    public function tbcargo ()
    {
        return $this->belongsTo(TbCargo::class,'cargo');
    }

    public function tbcentrocosto ()
    {
        return $this->belongsTo(TbCentroConsto::class,'centro_trabajo');
    }

    public function configuracion_salarial ()
    {
        return $this->belongsTo(TbConfiguracionSalarial::class,'configuracion_salarial');
    }

    public function tbdependencia () {
        return $this->belongsTo(TbDependencia::class,'dependencia');
    }

    public function sc_empleado ()
    {
        return $this->belongsTo(SCEmpleado::class,'empleado');
    }

    public function tb_tipocontrato () {
        return $this->belongsTo(TbTipoContrato::class,'tipo_contrato');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('sc_empleado', function ($query) use ($constraint) {
                $query->where('primer_nombre', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('primer_apellido', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('segundo_nombre', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('segundo_apellido', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('numero_identificacion', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }

}
