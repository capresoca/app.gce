<?php


namespace App\Capresoca\RecaudosSOI;


trait EncabezadoFieldsMap
{
    protected $fieldsMap = [
        ['field' => 'numero_registro', 'length' => 5],
        ['field' => 'tipo_registro', 'length' => 1],
        ['field' => 'codigo_formato', 'length' => 2],
        ['field' => 'nit', 'length' => 16],
        ['field' => 'digito_verificacion_nit', 'length' => 1],
        ['field' => 'razon_social_aportante', 'length' => 200],
        ['field' => 'tipo_documento_aportante', 'length' => 2],
        ['field' => 'identificacion_aportante', 'length' => 16],
        ['field' => 'digito_verificacion_aportante', 'length' => 1],
        ['field' => 'tipo_aportante', 'length' => 2],
        ['field' => 'direccion_correspondencia', 'length' => 40],
        ['field' => 'codigo_ciudad_municipio', 'length' => 3],
        ['field' => 'codigo_departamento', 'length' => 2],
        ['field' => 'telefono', 'length' => 10],
        ['field' => 'fax', 'length' => 10],
        ['field' => 'correo_electronico', 'length' => 60],
        ['field' => 'periodo_pago', 'length' => 7],
        ['field' => 'codigo_arl', 'length' => 6],
        ['field' => 'tipo_planilla', 'length' => 1],
        ['field' => 'fecha_pago_asociada', 'length' => 10],
        ['field' => 'fecha_pago', 'length' => 10],
        ['field' => 'numero_asociada', 'length' => 10],
        ['field' => 'numero_radicacion', 'length' => 10],
        ['field' => 'forma_presentacion', 'length' => 1],
        ['field' => 'codigo_sucursal', 'length' => 10],
        ['field' => 'nombre_sucursal', 'length' => 40],
        ['field' => 'numero_empleados', 'length' => 5],
        ['field' => 'numero_afiliados', 'length' => 5],
        ['field' => 'codigo_operador', 'length' => 2],
        ['field' => 'modalidad', 'length' => 1],
        ['field' => 'dias_mora', 'length' => 4],
        ['field' => 'numero_registros_salida', 'length' => 8],
        ['field' => 'fecha_matricula_mercantil', 'length' => 10],
        ['field' => 'codigo_departamento_34', 'length' => 2],
        ['field' => 'exonerado_pago_salud', 'length' => 1],
        ['field' => 'clase_aportante', 'length' => 1],
        ['field' => 'naturaleza_juridica', 'length' => 1],
        ['field' => 'tipo_persona', 'length' => 1],
        ['field' => 'fecha_archivo', 'length' => 10]
    ];
}