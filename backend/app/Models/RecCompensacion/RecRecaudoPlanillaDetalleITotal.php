<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

class RecRecaudoPlanillaDetalleITotal extends Model
{
    protected $table = 'rec_recaudo_planilla_detalle_i_totals';
    protected $primaryKey = 'consecutivo_recaudo_planilla_detalle_i_total';
    protected $fillable = [
        'acto_administrativo_ugpp',
        'contenido',
        'dias_mora',
        'fecha_apertura_liquidacion',
        'indicador_ugpp',
        'nombre_entidad_liquidacion',
        'consecutivo_recaudo_encabezado',
        'registro_intereses_mora',
        'registro_total_aportes',
        'registro_total_pagar',
        'tipo_registro_31',
        'tipo_registro_36',
        'tipo_registro_39',
        'tipo_registro_4',
        'total_cotizacion',
        'total_cotizacion_obligatoria',
        'total_ibc',
        'total_neto_aportes',
        'total_neto_upc_adicional',
        'total_upc_adicional',
        'valor_mora_cotizaciones',
        'valor_mora_upc_adicional',
        'valor_pagado_sancion'
    ];
}
