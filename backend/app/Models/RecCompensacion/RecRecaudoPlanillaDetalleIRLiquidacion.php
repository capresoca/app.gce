<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoPlanillaDetalleIRLiquidacion
 * @package App\Models\RecCompensacion
 */

class RecRecaudoPlanillaDetalleIRLiquidacion extends Model
{
    protected $primaryKey = 'consecutivo_recaudo_planilla_detalle_i_r_liquidacion';
    protected $fillable = [
        'consecutivo_novedad',
        'consecutivo_recaudo_encabezado',
        'contenido',
        'cotizacion_obligatoria',
        'departamento',
        'dias_cotizados',
        'errores',
        'fecha_fin_ige',
        'fecha_fin_irl',
        'fecha_fin_lma',
        'fecha_fin_sln',
        'fecha_fin_vac_lr',
        'fecha_fin_vct',
        'fecha_ingreso',
        'fecha_inicio_ige',
        'fecha_inicio_irl',
        'fecha_inicio_lma',
        'fecha_inicio_sln',
        'fecha_inicio_vac_lr',
        'fecha_inicio_vct',
        'fecha_inicio_vsp',
        'fecha_radicacion_exterior',
        'fecha_retiro',
        'ingreso_base_cotizacion',
        'municipio',
        'numero_identificacion_cotizante',
        'primer_apellido',
        'primer_nombre',
        'salario_basico',
        'secuencia',
        'segundo_apellido',
        'segundo_nombre',
        'subtipo_cotizante',
        'sw_colombiano_exterior',
        'sw_correcciones',
        'sw_exonerado_aportes',
        'sw_extranjero',
        'sw_ige',
        'sw_ing',
        'sw_lma',
        'sw_procesado',
        'sw_ret',
        'sw_salario_integral',
        'sw_sln',
        'sw_tae',
        'sw_tde',
        'sw_vac_lr',
        'sw_vsp',
        'sw_vst',
        'tarifa',
        'tipo_cotizante',
        'tipo_identificacion_cotizante',
        'tipo_registro',
        'valor_upc_adicional'
    ];
}
