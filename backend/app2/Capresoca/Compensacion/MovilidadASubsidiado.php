<?php


namespace App\Capresoca\Compensacion;


use App\Capresoca\AfiliadoTrait;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsEps;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\AsTraslatramite;
use App\Models\General\GnEmpresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MovilidadASubsidiado
{
    static function mover(){
        $candidatos = DB::select('select * from(select 
            as_afiliado_pagador.as_afiliado_id,as_afiliado_pagador.id as relaboral,
            (select concat(periodo,\'-\', if(cp_liquidaciones.dias_pagados < 10, 
                concat(\'0\',cp_liquidaciones.dias_pagados),cp_liquidaciones.dias_pagados))
                from cp_liquidaciones 
                where cp_liquidaciones.relacion_laboral_id = as_afiliado_pagador.id 
                  and cp_liquidaciones.retiro = 1 and cp_liquidaciones.periodo > \'2015-12\'
                order by periodo desc 
                limit 1
            ) as fecha_retiro, as_afiliados.identificacion
            from as_afiliado_pagador join as_afiliados on as_afiliados.id = as_afiliado_pagador.as_afiliado_id
            where as_afiliados.as_regimene_id = 1
            having fecha_retiro is not null
            order by as_afiliado_id, fecha_retiro desc) as t1 group by as_afiliado_id;');



        foreach ($candidatos as $candidato) {
            $posterioresLiquidaciones = DB::select("select as_afiliados.id from as_afiliados
                join as_afiliado_pagador on as_afiliados.id = as_afiliado_pagador.as_afiliado_id
                join cp_liquidaciones on cp_liquidaciones.relacion_laboral_id = as_afiliado_pagador.id
                where as_afiliados.id = ".$candidato->as_afiliado_id." and cp_liquidaciones.periodo > '".substr($candidato->fecha_retiro,0,-3).
                "' and as_afiliado_pagador.id !=".$candidato->relaboral);


            if(count($posterioresLiquidaciones)){
                continue;
            }

            $afiliado = AsAfiliado::find($candidato->as_afiliado_id);

            $fechaMovilidad = explode('-',$candidato->fecha_retiro);

            $fechaMovilidad[2] = $fechaMovilidad[2] > 30 ? 30 : $fechaMovilidad[2];

            $fechaMovilidad = implode('-',$fechaMovilidad);

            $fechaMovilidad = Carbon::parse($fechaMovilidad)->addDay()->toDateString();

            self::crearTramiteTraslado($afiliado, $fechaMovilidad);

            foreach ($afiliado->beneficiarios as $beneficiario) {
                self::crearTramiteTraslado($beneficiario,$fechaMovilidad);
            }

        }


    }

    public static function crearTramiteTraslado(AsAfiliado $afiliado,$fechaMovilidad)
    {
        $formulario = AsFormtrasmov::where([
            'as_afiliado_id'        => $afiliado->id,
            'fecha_traslado'        => $fechaMovilidad
        ]);

        if($formulario->count()){
            return;
        }
        $formulario = AsFormtrasmov::create([
            'tipo'                  => 'Movilidad',
            'as_afiliado_id'        => $afiliado->id,
            'identificacion'        => $afiliado->identificacion,
            'as_eps_id'             => AsEps::where('cod_habilitacion','EPSC25')->first()->id,
            'gn_tipdocidentidad_id' => $afiliado->gn_tipdocidentidad_id,
            'fecha_expedicion'      => $afiliado->fecha_expedicion,
            'nombre1'               => $afiliado->nombre1,
            'nombre2'               => $afiliado->nombre2,
            'apellido1'             => $afiliado->apellido1,
            'apellido2'             => $afiliado->apellido2,
            'fecha_nacimiento'      => $afiliado->fecha_nacimiento,
            'gn_sexo_id'            => $afiliado->gn_sexo_id,
            'estado'                => 'Radicado',
            'fecha_traslado'        => $fechaMovilidad,
            'fecha_radicacion'      => today()->toDateString()
        ]);

        $tramite = AsTramite::create([
            'tipo_tramite' => 'Traslado Subsidiado',
            'clase_tramite' => 'Automatico',
            'fecha_radicacion' => today()->toDateString(),
            'estado' => 'Radicado',
            'gs_usuradica_id' => 1,
            'gs_usuario_id' => 1
        ]);

        AsTraslatramite::create([
            'as_afiliado_id' => $afiliado->id,
            'as_tramite_id' => $tramite->id,
            'as_formtrasmov_id' => $formulario->id,
            'codigo_entidad' => 'EPSC25'
        ]);
    }

}