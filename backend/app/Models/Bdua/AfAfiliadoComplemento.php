<?php
namespace App\Models\Bdua;

use Illuminate\Database\Eloquent\Model;

class AfAfiliadoComplemento extends Model
{
    protected $table = 'af_afiliado_complemento';
    protected $primaryKey = 'afiliado';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = [
        'afiliado',
        'estado_liquidacion',
        'ultimo_periodo_liquidacion',
        'fecha_ultimo_periodo_liquidacion',
        'serial_fosyga',
        'sw_fallecido',
        'sw_modalidad_novalida',
        'sw_inconsistencia',
        'sw_deduciones',
        'sw_pensionados',
        'sw_duplicados_foneticos',
        'sw_regexcepcion',
        'gn_municipio_id',
        'sw_bloqueado_neg',
        'ultimo_periodo_bloqueado',
        'tipo_afiliacion',
        'tipo_regimen',
        'tipo_condicion',
        'ultimo_fecha_cambio',
        'tipo_cambio',
        'consecutivo_tipo_cotizante',
        'afiliado_padre',
        'consecutivo_parentesco_codificado',
        'parentesco',
        'condicion_beneficiario',
        'estado_rc',
        'afiliado_padre_real',
        'categoria_ibc',
        'semanas_cotizadas',
        'glosa_bdua',
        'fecha_glosa_bdua',
        'auditoria_bdua'
    ];
}

