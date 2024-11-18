<?php


namespace App\Capresoca\RecaudosSOI;


trait CuerpoIPFields
{
    protected $fieldsMap = [
        ['field' => 'secuencia', 'length' => 7],
        ['field' => 'tipo_registro', 'length' => 1],
        ['field' => 'tipo_documento_cotizante', 'length' => 2],
        ['field' => 'identificacion_cotizante', 'length' => 16],
        ['field' => 'primer_apellido', 'length' => 20],
        ['field' => 'segundo_apellido', 'length' => 30],
        ['field' => 'primer_nombre', 'length' => 20],
        ['field' => 'segundo_nombre', 'length' => 30],
        ['field' => 'primer_apellido_causante', 'length' => 20],
        ['field' => 'segundo_apellido_causante', 'length' => 30],
        ['field' => 'primer_nombre_causante', 'length' => 20],
        ['field' => 'segundo_nombre_causante', 'length' => 30],
        ['field' => 'tipo_documento_causante', 'length' => 2],
        ['field' => 'documento_causante', 'length' => 16],
        ['field' => 'tipo_pension', 'length' => 2],
        ['field' => 'pension_compartida', 'length' => 1],
        ['field' => 'tipo_pensionado', 'length' => 1],
        ['field' => 'pensionado_exterior','length' => 1],
        ['field' => 'departamento_residencia','length' => 2],
        ['field' => 'municipio_residencia','length' => 3],
        ['field' => 'ingreso','length' => 1],
        ['field' => 'retiro','length' => 1],
        ['field' => 'tde','length' => 1],
        ['field' => 'tae','length' => 1],
        ['field' => 'vsp', 'length' => 1],
        ['field' => 'sus','length' => 1],
        ['field' => 'dias_Cotizados','length' => 2],
        ['field' => 'ibc','length' => 9],
        ['field' => 'tarifa','length' => 7],
        ['field' => 'cotizacion_obligatoria','length' => 9],
        ['field' => 'upc_adicional','length' => 9],
        ['field' => 'fecha_de_ingreso_formato','length' => 10],
        ['field' => 'fecha_de_retiro_formato','length' => 10],
        ['field' => 'fecha_inicio_vsp','length' => 10],
        ['field' => 'valor_mesada_pensional','length' => 9],
    ];

}

