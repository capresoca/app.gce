<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoPlanillaDetalleARe
 * @package App\Models\RecCompensacion
 */

class RecRecaudoPlanillaDetalleARe extends Model
{

    protected $primaryKey = 'consecutivo_recaudo_planilla_detalle_a_r';
    protected $fillable = [
        'actividad_economica',
        'clase_aportante',
        'codigo_operador',
        'codigo_sucursal',
        'consecutivo_recaudo_encabezado',
        'contenido',
        'correo_electronico',
        'departamento',
        'departamento_aportante',
        'digito_verificacion_aportante',
        'digito_verificacion_representante',
        'direccion_aportante',
        'errores',
        'fax',
        'fecha_inicio_accion',
        'fecha_matricula_mercantil',
        'fecha_terminacion_actividades',
        'forma_presentacion',
        'municipio',
        'naturaleza_juridica',
        'nombre_archivo',
        'nombre_sucursal',
        'numero_identificacion_aportante',
        'numero_identificacion_representante',
        'periodo_pago',
        'primer_apellido_representante',
        'primer_nombre_representante',
        'razon_social_aportante',
        'segundo_apellido_representante',
        'segundo_nombre_representante',
        'sw_caja_compensacion',
        'sw_exonerado_pago',
        'telefono',
        'tipo_accion',
        'tipo_aportante',
        'tipo_identificacion_aportante',
        'tipo_identificacion_representante',
        'tipo_persona'
    ];
}
