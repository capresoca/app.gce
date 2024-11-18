<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoPlanillaDetalleA
 * @package App\Models\RecCompensacion
 */

class RecRecaudoPlanillaDetalleA extends Model
{
    use PimpableTrait;

    protected $primaryKey = 'consecutivo_recaudo_planilla_detalle_a';
    protected $fillable = [
        'consecutivo_recaudo_encabezado',
        'razon_social_aportante',
        'tipo_identificacion_aportante',
        'numero_identificacion_aportante',
        'digito_verificacion_aportante',
        'codigo_sucursal',
        'nombre_sucursal',
        'clase_aportante',
        'naturaleza_juridica',
        'tipo_persona',
        'forma_presentacion',
        'direccion_aportante',
        'departamento',
        'municipio',
        'actividad_economica',
        'telefono',
        'fax',
        'correo_electronico',
        'tipo_identificacion_representante',
        'numero_identificacion_representante',
        'digito_verificacion_representante',
        'primer_apellido_representante',
        'segundo_apellido_representante',
        'primer_nombre_representante',
        'segundo_nombre_representante',
        'fecha_inicio_accion',
        'tipo_accion',
        'fecha_terminacion_actividades',
        'codigo_operador',
        'periodo_pago',
        'tipo_aportante',
        'fecha_matricula_mercantil',
        'departamento_aportante',
        'sw_exonerado_pago',
        'sw_caja_compensacion',
        'contenido',
        'errores',
        'nombre_archivo'
    ];

    protected $searchable = ['search'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('razon_social_aportante', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
}
