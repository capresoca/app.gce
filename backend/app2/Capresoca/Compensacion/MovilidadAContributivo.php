<?php


namespace App\Capresoca\Compensacion;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsEps;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\AsTraslatramite;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MovilidadAContributivo
{
    static function mover(){
        $candidatos = DB::select('select *,as_pagadores.id as aportante from as_afiliados 
	            join as_afiliado_pagador on as_afiliados.id = as_afiliado_pagador.as_afiliado_id
	            join as_pagadores on as_afiliado_pagador.as_pagador_id = as_pagadores.id
	            join cp_liquidaciones on cp_liquidaciones.relacion_laboral_id = as_afiliado_pagador.id
	            where as_afiliados.as_regimene_id = 2 and cp_liquidaciones.periodo >= \'2019-03\' 
	              and cp_liquidaciones.periodo <= \'2019-06\' 
	              and cp_liquidaciones.ingreso = 1 and as_afiliados.estado = 3;');

        foreach ($candidatos as $candidato) {

            $afiliado = AsAfiliado::find($candidato->as_afiliado_id);


            $fechaMovilidad = Carbon::parse($candidato->fecha_vinculacion)->addDay()->toDateString();

            $formulario = AsFormtrasmov::where([
                'as_afiliado_id'        => $afiliado->id,
                'fecha_traslado'        => $fechaMovilidad,
                'tipo'                  => 'Movilidad',
                'as_eps_id' => 307
            ]);

            if($formulario->count()){
                continue;
            }

            $formulario = AsFormtrasmov::create([
                'tipo'                  => 'Movilidad',
                'as_afiliado_id'        => $afiliado->id,
                'identificacion'        => $afiliado->identificacion,
                'as_eps_id'             => AsEps::where('cod_habilitacion','EPS025')->first()->id,
                'gn_tipdocidentidad_id' => $afiliado->gn_tipdocidentidad_id,
                'fecha_expedicion'      => $afiliado->fecha_expedicion,
                'nombre1'               => $afiliado->nombre1,
                'nombre2'               => $afiliado->nombre2,
                'apellido1'             => $afiliado->apellido1,
                'as_clasecotizante_id'  => $candidato->aportante == $candidato->identificacion ? 3 : 1,
                'apellido2'             => $afiliado->apellido2,
                'fecha_nacimiento'      => $afiliado->fecha_nacimiento,
                'gn_sexo_id'            => $afiliado->gn_sexo_id,
                'estado'                => 'Radicado',
                'fecha_traslado'        => $fechaMovilidad,
                'as_pagadore_id'        => $candidato->aportante,
                'fecha_radicacion'      => today()->toDateString()
            ]);

            $tramite = AsTramite::create([
                'tipo_tramite' => 'Traslado Contributivo',
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
                'as_tipafiliado_id' => 1,
                'codigo_entidad' => 'EPSC25'
            ]);

        }


    }
}