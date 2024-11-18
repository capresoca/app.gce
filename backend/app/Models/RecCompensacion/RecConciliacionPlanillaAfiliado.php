<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecConciliacionPlanillaAfiliado
 * @package App\Models\RecCompensacion
 */

class RecConciliacionPlanillaAfiliado extends Model
{
    protected $primaryKey = 'consecutivo_conciliacion';
    protected $fillable = [
        'afiliado',
        'consecutivo_aportante',
        'consecutivo_compensacion',
        'consecutivo_inconsistencia',
        'consecutivo_ingreso_contributivo',
        'consecutivo_recaudo_encabezado',
        'cotizacion_obligatoria_planilla',
        'dias_cotizados_planilla',
        'dias_cotizados_siic',
        'estado_siic',
        'fecha_grabado',
        'fecha_ingreso_siic',
        'fecha_retiro_siic',
        'fecha_revalida',
        'ibc_planilla',
        'ibc_siic',
        'inexactitudes',
        'regimen_siic',
        'secuencia',
        'secuencia_compensacion',
        'secuencia_inconsistencia',
        'secuencias_planilla',
        'sub_estado',
        'sw_salario_minimo',
        'tipo_cotizante_planilla',
        'tipo_cotizante_siic',
        'usuario_grabado',
        'usuario_revalida',
        'valor_cartera',
        'valor_liquidacion_esperada'
    ];
}
