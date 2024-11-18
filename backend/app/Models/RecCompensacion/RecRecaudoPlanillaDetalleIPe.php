<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Jorge Eduardo Hernández Oropeza 09/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoPlanillaDetalleIPe
 * @package App\Models\RecCompensacion
 */

class RecRecaudoPlanillaDetalleIPe extends Model
{
    protected $table = 'rec_recaudo_planilla_detalle_i_pes';
    protected $primaryKey = 'consecutivo_recaudo_planilla_detalle_i_p';
    protected $fillable = [
        'clase_pagador',
        'codigo_formato',
        'codigo_operador',
        'codigo_sucursal',
        'consecutivo_log_bancario_aportante',
        'consecutivo_log_bancario_aportante_sgp',
        'consecutivo_recaudo_encabezado',
        'contenido',
        'correo_electronico',
        'departamento',
        'dias_mora',
        'digito_verificacion_administradora',
        'digito_verificacion_pagador',
        'direccion_correspondencia',
        'errores',
        'fax',
        'fecha_actualizacion',
        'fecha_conciliacion',
        'fecha_pago',
        'forma_presentacion',
        'municipio',
        'nit_administradora',
        'nombre_archivo',
        'nombre_sucursal',
        'numero_afiliados',
        'numero_identificacion_pagador',
        'numero_pensionados',
        'numero_registro',
        'numero_registros_tipo_2',
        'periodo_pago',
        'razon_social_pagador',
        'sw_conciliacion',
        'telefono',
        'tipo_error',
        'tipo_identificacion_pagador',
        'tipo_planilla',
        'tipo_registro',
        'usuario_conciliacion'
    ];

    public function recLogBancarioDetalleAportante ()
    {
        return $this->belongsTo(RecLogBancarioDetalleAportante::class,'consecutivo_log_bancario_aportante');
    }

    public function recLogBancarioDetalleAportanteSgp ()
    {
        return $this->belongsTo(RecLogBancarioDetalleAportanteSgp::class,'consecutivo_log_bancario_aportante_sgp');
    }

    public function recRecaudoPlanillaEncabezado () {
        return $this->belongsTo(RecRecaudoPlanillaEncabezado::class,'consecutivo_recaudo_encabezado');
    }
}
