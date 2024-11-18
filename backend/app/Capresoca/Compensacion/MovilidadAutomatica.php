<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26/03/2019
 * Time: 11:06 AM
 */

namespace App\Capresoca\Compensacion;


use App\Compensacion\CpAporte;
use App\Models\Aseguramiento\AsEps;
use App\Models\Aseguramiento\AsFormtrasmov;

abstract class MovilidadAutomatica
{
    public function paraContributivo(CpAporte $aporte)
    {
        //Afiliado
        $afiliado = $aporte->liquidacion->relacionLaboral->afiliado;
        //crea formtrasmov con sus beneficiarios



        $formulario = AsFormtrasmov::create([
            'tipo'                  => 'Movilidad',
            'as_afiliado_id'        => $afiliado->id,
            'identificacion'        => $afiliado->identificacion,
            'as_eps_id'             => AsEps::where('cod_habilitacion','EPS025')->first()->id,
            'gn_tipdocidentidad_id' => $afiliado->gn_tipdocidentidad_id,
            'as_clasecotizante_id'  => $aporte->tipo_cotizante,
            'as_tipafiliado_id'     => $aporte->as_tipafiliado_id,
            'fecha_expedicion'      => $afiliado->fecha_expedicion,
            'nombre1'               => $afiliado->nombre1,
            'nombre2'               => $afiliado->nombre2,
            'apellido1'             => $afiliado->apellido1,
            'apellido2'             => $afiliado->apellido2,
            'fecha_nacimiento'      => $afiliado->fecha_nacimiento,
            'gn_sexo_id'            => $afiliado->gn_sexo_id,
            'estado'                => 'Registrado',
            'fecha_traslado'        => $aporte->liquidacion->periodo-'01'
        ]);


    }
}
