<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

class RecRecaudoPlanillaDetalleIPTotale extends Model
{
    protected $primaryKey = 'consecutivo_planilla_detalle_i_p_total';
    protected $fillable = [
        'consecutivo_recaudo_encabezado',
        'contenido',
        'dias_mora',
        'registro_aportes_intereses',
        'registro_intereses_mora',
        'registro_total_aportes',
        'registro_total_pagar',
        'tipo_registro_31',
        'tipo_registro_36',
        'tipo_registro_37',
        'tipo_registro_39',
        'total_aporte_fosyga',
        'total_cotizacion_obligatoria',
        'total_ibc',
        'total_upc_adicional',
        'valor_aporte_fosyga',
        'valor_cotizacion_obligatoria',
        'valor_cotizacion_obligatoria_intereses',
        'valor_ibc',
        'valor_ibc_intereses',
        'valor_mora_cotizaciones',
        'valor_mora_upc_adicional',
        'valor_upc_adicional'
    ];
}
