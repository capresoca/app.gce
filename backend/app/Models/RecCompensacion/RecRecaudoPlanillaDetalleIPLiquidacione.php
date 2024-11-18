<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

class RecRecaudoPlanillaDetalleIPLiquidacione extends Model
{
    protected $primaryKey = 'consecutivo_recaudo_planilla_i_p_liquidacion';
    protected $fillable = [
        'consecutivo_novedad',
        'consecutivo_recaudo_encabezado',
        'contenido',
        'cotizacion_obligatoria',
        'departamento_residencia',
        'dias_cotizados',
        'errores',
        'fecha_fin_sus',
        'fecha_ingreso',
        'fecha_inicio_sus',
        'fecha_inicio_vsp',
        'fecha_radicacion_exterior',
        'fecha_retiro',
        'ingreso_base_cotizacion',
        'municipio_residencia',
        'numero_identificacion_causante',
        'numero_identificacion_pensionado',
        'primer_apellido_causante',
        'primer_apellido_pensionado',
        'primer_nombre_causante',
        'primer_nombre_pensionado',
        'secuencia',
        'segundo_apellido_causante',
        'segundo_apellido_pensionado',
        'segundo_nombre_causante',
        'segundo_nombre_pensionado',
        'sw_ing',
        'sw_pension_compartida',
        'sw_pensionado_exterior',
        'sw_procesado',
        'sw_ret',
        'sw_sus',
        'sw_tae',
        'sw_tde',
        'sw_vsp',
        'tarifa',
        'tipo_identificacion_causante',
        'tipo_identificacion_pensionado',
        'tipo_pension',
        'tipo_pensionado',
        'tipo_registro',
        'valor_mesada_pensional',
        'valor_upc_adicional'
    ];

    protected $appends = ['recaudo_planilla_detalle_ip'];

    public function recaudo_planilla_encabezado()
    {
        return $this->belongsTo(RecRecaudoPlanillaEncabezado::class,'consecutivo_recaudo_encabezado');
    }

    public function getRecaudoPlanillaDetalleIpAttribute()
    {
        $planilladetalleIP = RecRecaudoPlanillaDetalleIPe::where('consecutivo_recaudo_encabezado', '=',$this->attributes['consecutivo_recaudo_encabezado'])->first();

        return $planilladetalleIP;
    }

}
