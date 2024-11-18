<?php

namespace App\Models\Compensacion;

use Illuminate\Database\Eloquent\Model;

class CpPiladetalle extends Model
{
    protected $fillable = [
        'secuencia',
        'tipo_registro',
        'tipo_documento_cotizante',
        'identificacion_cotizante',
        'tipo_cotizante',
        'subtipo_cotizante',
        'extranjero_no_oblig',
        'col_exterior',
        'cod_dep_laboral',
        'cod_mun_laboral',
        'primer_apellido',
        'segundo_apellido',
        'primer_nombre',
        'segundo_nombre',
        'ingreso',
        'retiro',
        'tde',
        'tae',
        'vsp',
        'vst',
        'sln',
        'ige',
        'lma',
        'vac_lr',
        'dias_cotizados',
        'salario_basico',
        'base_cotizacion',
        'tarifa',
        'cotizacion_obligatoria',
        'valor_upc_adicional',
        'correcciones',
        'salario_integral',
        'exonerado_pago_salud',
        'fecha_ingreso',
        'fecha_retiro',
        'fecha_inicio_vsp',
        'fecha_inicio_sln',
        'fecha_fin_sln',
        'fecha_inicio_ige',
        'fecha_fin_ige',
        'fecha_inicio_lma',
        'fecha_fin_lma',
        'fecha_inicio_vac_lr',
        'fecha_fin_vac_lr',
        'fecha_inicio_vct',
        'fecha_fin_vct',
        'fecha_inicio_irl',
        'fecha_fin_irl',
        'cp_pila_id'
    ];

    public function pila() {
        return $this->belongsTo(CpPila::class, 'cp_pila_id');
    }
}
