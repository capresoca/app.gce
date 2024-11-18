<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoPlanillaDetalleAPe
 * @package App\Models\RecCompensacion
 */

class RecRecaudoPlanillaDetalleAPe extends Model
{
    protected $primaryKey = 'rec_recaudo_planilla_detalle_a_p';
    protected $fillable = [
        'consecutivo_recaudo_encabezado',
        'actividad_economica',
        'clase_pagador',
        'codigo_operador',
        'codigo_sucursal',
        'contenido',
        'correo_electronico',
        'departamento',
        'digito_verificacion_pagador',
        'digito_verificacion_representante',
        'direccion_pagador',
        'errores',
        'fax',
        'forma_presentacion',
        'municipio',
        'naturaleza_juridica',
        'nombre_archivo',
        'nombre_sucursal',
        'numero_identificacion_pagador',
        'numero_identificacion_representante',
        'periodo_pago',
        'primer_apellido_representante',
        'primer_nombre_representante',
        'razon_social_pagador',
        'segundo_apellido_representante',
        'segundo_nombre_representante',
        'telefono',
        'tipo_identificacion_pagador',
        'tipo_identificacion_representante',
        'tipo_pagador',
        'tipo_persona'
    ];
}
