<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoPlanillaDetalleI
 * @package App\Models\RecCompensacion
 */

class RecRecaudoPlanillaDetalleI extends Model
{
    protected $table = 'rec_recaudo_planilla_detalle_is';
    protected $primaryKey = 'consecutivo_recaudo_planilla_detalle_i';
    protected $fillable = [
        'consecutivo_recaudo_encabezado',
        'numero_registro',
        'tipo_registro',
        'codigo_formato',
        'nit_administradora',
        'digito_verificacion_administradora',
        'razon_social_aportante',
        'tipo_identificacion_aportante',
        'numero_identificacion_aportante',
        'digito_verificacion_aportante',
        'tipo_aportante',
        'direccion_correspondencia',
        'municipio',
        'departamento',
        'telefono',
        'fax',
        'correo_electronico',
        'periodo_pago',
        'codigo_arl',
        'tipo_planilla',
        'fecha_pago_planilla',
        'fecha_pago',
        'numero_planilla',
        'numero_radicacion_pila',
        'forma_presentacion',
        'codigo_sucursal',
        'nombre_sucursal',
        'numero_empleados',
        'numero_afiliados',
        'codigo_operador',
        'modalidad_planilla',
        'dias_mora',
        'numero_registros_tipo_2',
        'fecha_matricula_mercantil',
        'departamento_aportante',
        'sw_exonerado_pago_aportes',
        'clase_aportante',
        'naturaleza_juridica',
        'tipo_persona',
        'contenido',
        'errores',
        'nombre_archivo',
        'tipo_error',
        'usuario_conciliacion',
        'fecha_conciliacion',
        'sw_conciliacion',
        'fecha_actualizacion',
        'consecutivo_log_bancario_aportante',
        'consecutivo_log_bancario_aportante_sgp'
    ];

    public function recLogBancarioDetalleAportante () {
        return $this->belongsTo(RecLogBancarioDetalleAportante::class,'consecutivo_log_bancario_aportante');
    }

    public function recLogBancarioDetalleAportanteSgp () {
        return $this->belongsTo(RecLogBancarioDetalleAportanteSgp::class,'consecutivo_log_bancario_aportante_sgp');
    }

    public function recRecaudoPlanillaEncabezado()
    {
        return $this->belongsTo(RecRecaudoPlanillaEncabezado::class,'consecutivo_recaudo_encabezado');
    }


}
