<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoPlanillaDetalleIRe
 * @package App\Models\RecCompensacion
 */

class RecRecaudoPlanillaDetalleIRe extends Model
{
    protected $primaryKey = 'consecutivo_recaudo_planilla_detalle';
    protected $fillable = [
        'clase_aportante',
        'codigo_arl',
        'codigo_formato',
        'codigo_operador',
        'codigo_sucursal',
        'consecutivo_recaudo_encabezado',
        'contenido',
        'correo_electronico',
        'departamento',
        'departamento_aportante',
        'dias_mora',
        'digito_verificacion_administradora',
        'digito_verificacion_aportante',
        'direccion_correspondencia',
        'errores',
        'fax',
        'fecha_actualizacion',
        'fecha_matricula_mercantil',
        'fecha_pago',
        'fecha_pago_planilla',
        'forma_presentacion',
        'modalidad_planilla',
        'municipio',
        'naturaleza_juridica',
        'nit_administradora',
        'nombre_archivo',
        'nombre_sucursal',
        'numero_afiliados',
        'numero_empleados',
        'numero_identificacion_aportante',
        'numero_planilla',
        'numero_radicacion_pila',
        'numero_registro',
        'numero_registros_tipo_2',
        'periodo_pago',
        'razon_social_aportante',
        'sw_exonerado_pago_aportes',
        'telefono',
        'tipo_aportante',
        'tipo_identificacion_aportante',
        'tipo_persona',
        'tipo_planilla',
        'tipo_registro'
    ];

}