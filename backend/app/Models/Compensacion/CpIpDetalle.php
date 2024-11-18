<?php

namespace App\Models\Compensacion;

use Illuminate\Database\Eloquent\Model;

class CpIpDetalle extends Model
{
    protected $fillable = [
        'secuencia',
        'tipo_registro',
        'tipo_documento_cotizante',
        'identificacion_cotizante',
        'primer_apellido',
        'segundo_apellido',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido_causante',
        'segundo_apellido_causante',
        'primer_nombre_causante',
        'segundo_nombre_causante',
        'tipo_documento_causante',
        'documento_causante',
        'tipo_pension',
        'pension_compartida',
        'tipo_pensionado',
        'pensionado_exterior',
        'departamento_residencia',
        'municipio_residencia',
        'ingreso',
        'retiro',
        'tde',
        'tae',
        'vsp',
        'sus',
        'dias_Cotizados',
        'ibc',
        'tarifa',
        'cotizacion_obligatoria',
        'upc_adicional',
        'fecha_de_ingreso_formato',
        'fecha_de_retiro_formato',
        'fecha_inicio_vsp',
        'valor_mesada_pensional'
    ];
}
