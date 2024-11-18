<?php


namespace App\Capresoca\RecaudosSOI;


trait CuerpoFieldsMap
{
    protected $fieldsMap = [
        ['field' => 'secuencia', 'length' => 5],
        ['field' => 'tipo_registro', 'length' => 1],
        ['field' => 'tipo_documento_cotizante', 'length' => 2],
        ['field' => 'identificacion_cotizante', 'length' => 16],
        ['field' => 'tipo_cotizante', 'length' => 2],
        ['field' => 'subtipo_cotizante', 'length' => 2],
        ['field' => 'extranjero_no_oblig', 'length' => 1],
        ['field' => 'col_exterior', 'length' => 1],
        ['field' => 'cod_dep_laboral', 'length' => 2],
        ['field' => 'cod_mun_laboral', 'length' => 3],
        ['field' => 'primer_apellido', 'length' => 20],
        ['field' => 'segundo_apellido', 'length' => 30],
        ['field' => 'primer_nombre', 'length' => 20],
        ['field' => 'segundo_nombre', 'length' => 30],
        ['field' => 'ingreso', 'length' => 1],
        ['field' => 'retiro', 'length' => 1],
        ['field' => 'tde', 'length' => 1],
        ['field' => 'tae', 'length' => 1],
        ['field' => 'vsp', 'length' => 1],
        ['field' => 'vst', 'length' => 1],
        ['field' => 'sln', 'length' => 1],
        ['field' => 'ige', 'length' => 1],
        ['field' => 'lma', 'length' => 1],
        ['field' => 'vac_lr', 'length' => 1],
        ['field' => 'dias_cotizados', 'length' => 2],
        ['field' => 'salario_basico', 'length' => 9],
        ['field' => 'base_cotizacion', 'length' => 9],
        ['field' => 'tarifa', 'length' => 7],
        ['field' => 'cotizacion_obligatoria', 'length' => 9],
        ['field' => 'valor_upc_adicional', 'length' => 9],
        ['field' => 'correcciones', 'length' => 1],
        ['field' => 'salario_integral', 'length' => 1],
        ['field' => 'exonerado_pago_salud', 'length' => 1],
        ['field' => 'fecha_ingreso', 'length' => 10],
        ['field' => 'fecha_retiro', 'length' => 10],
        ['field' => 'fecha_inicio_vsp', 'length' => 10],
        ['field' => 'fecha_inicio_sln', 'length' => 10],
        ['field' => 'fecha_fin_sln', 'length' => 10],
        ['field' => 'fecha_inicio_ige', 'length' => 10],
        ['field' => 'fecha_fin_ige', 'length' => 10],
        ['field' => 'fecha_inicio_lma', 'length' => 10],
        ['field' => 'fecha_fin_lma', 'length' => 10],
        ['field' => 'fecha_inicio_vac_lr', 'length' => 10],
        ['field' => 'fecha_fin_vac_lr', 'length' => 10],
        ['field' => 'fecha_inicio_vct', 'length' => 10],
        ['field' => 'fecha_fin_vct', 'length' => 10],
        ['field' => 'fecha_inicio_irl', 'length' => 10],
        ['field' => 'fecha_fin_irl', 'length' => 10]
    ];
}