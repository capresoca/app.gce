<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoPlanillaDetalleIRTotal
 * @package App\Models\RecCompensacion
 */

class RecRecaudoPlanillaDetalleIRTotal extends Model
{
    protected $primaryKey = 'consecutivo_recaudo_planilla_detalle_i_r_total';
    protected $fillable = [
        'consecutivo_recaudo_encabezado',
        'contenido',
        'dias_mora',
        'registro_intereses_mora',
        'registro_total_pagar',
        'tipo_registro_31',
        'tipo_registro_36',
        'tipo_registro_39',
        'total_cotizacion',
        'total_cotizacion_obligatoria',
        'total_ibc',
        'total_neto_aportes',
        'total_neto_upc_adicional',
        'total_upc_adicional',
        'valor_mora_cotizaciones',
        'valor_mora_upc_adicional'
    ];
}
