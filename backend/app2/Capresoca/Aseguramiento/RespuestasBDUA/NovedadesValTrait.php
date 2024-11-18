<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17/09/2018
 * Time: 11:10 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA;


use App\Traits\ToolsTrait;
use App\Models\Aseguramiento\{
    AsAfiliadoPagador,
    AsClasecotizante,
    AsCondicionDiscapacidade,
    AsCondterminacione,
    AsEstadoafiliado,
    AsPagadore,
    AsParentesco,
    AsPobespeciale,
    AsAfiliado,
    AsTipafiliado
};
use App\Models\General\{
    GnMunicipio, GnSexo, GnTipdocidentidade, GnZona
};

use App\Models\Niif\NfCiiu;

trait NovedadesValTrait
{
    

    //Implementada
    protected function N01(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $tipo_id = GnTipdocidentidade::where('abreviatura', $novedad[13])->first();
        
        if(!$tipo_id){
            throw new \Exception('Tipo de identificacion no es valida: '.$novedad[13]);
        }
        $fecha_nac = ToolsTrait::homologarFecha($novedad[15]);

        $oldValues = [
            'tipo_identificacion' => $afiliado->gn_tipdocidentidad_id,
            'identificacion' => $afiliado->identificacion,
            'fecha_nacimiento' => $afiliado->fecha_nacimiento
        ];

        $newValues = [
            'tipo_identificacion' => $novedad[13],
            'identificacion' => $novedad[14],
            'fecha_nacimiento' => $novedad[15]
        ];

        $afiliado->gn_tipdocidentidad_id = $tipo_id->id;
        $afiliado->identificacion = $novedad[3];
        $afiliado->fecha_nacimiento = $fecha_nac;

        $afiliado->save();

        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);
    }
    //Implementada
    protected function N02(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'nombre1' => $afiliado->nombre1,
            'nombre2' => $afiliado->nombre2
        ];

        $afiliado->nombre1 = $novedad[13];
        $afiliado->nombre2 = $novedad[14];
        $afiliado->save();

        $newValues = [
            'nombre1' => $novedad[13],
            'nombre2' => $novedad[14]
        ];


        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);

    }
    //Implementada
    protected function N03(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'apellido1' => $afiliado->apellido1,
            'apellido2' => $afiliado->apellido2
        ];

        $afiliado->apellido1 = $novedad[13];
        $afiliado->apellido2 = $novedad[14];
        $afiliado->save();

        $newValues = [
            'apellido1' => $novedad[13],
            'apellido2' => $novedad[14]
        ];


        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);

    }
    //Implementada
    protected function N04(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'municipio' => $afiliado->municipio->codigo,
        ];

        $nuevoMunicipio = GnMunicipio::where('codigo', $novedad[13].$novedad[14])->first();

        if(!$nuevoMunicipio){
            throw new \Exception('Codigo del municipio no es valido: '.$novedad[13].$novedad[14]);
        }
        $afiliado->gn_municipio_id = $nuevoMunicipio->id;
        $afiliado->save();

        $newValues = [
            'municipio' => $afiliado->municipio->codigo,
        ];

        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);

    }
    //Implementada
    protected function N05(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'cabfamilia_anterior' => $afiliado->cabeza_anterior->nombre_completo,
            'cabfamilia' => $afiliado->cabeza->nombre_completo
        ];

        $afiliado->cabfamilianterior_id = $afiliado->cabfamilia_id;
        $afiliado->cabfamilia_id = null;
        $afiliado->as_tipafiliado_id = 2;

        $afiliado->save();


        $newValues = [
            'cabfamilia_anterior' => $afiliado->cabeza_anterior->tercero->nombre_completo,
            'cabfamilia' => null
        ];

        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);

    }
    //Implementada
    protected function N06(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $clase_cotizante = AsClasecotizante::where('codigo', $novedad[13])->first();
        
        if(!$clase_cotizante){
            throw new \Exception('Clase de cotizante no es valida: '.$novedad[13]);
        }
        $tipo_documento_aportante = GnTipdocidentidade::where('abreviatura',$this->getTraslateTipoIdentificacionAportante($novedad[14]))->first();
        
        if(!$tipo_documento_aportante){
            throw new \Exception('Tipo de documento del aportante no es valida: '.$novedad[14]);
        }
        $actividad = NfCiiu::where('codigo', $novedad[16])->first();

        if(!$actividad){
            throw new \Exception('La actividad economica no es valida: '.$novedad[16]);
        }
        $aportante = AsPagadore::whereHas('tercero', function ($query) use ($novedad, $tipo_documento_aportante){
            $query->where('identificacion', $novedad[15])
                    ->where('gn_tipdocidentidad_id', $tipo_documento_aportante->id);
        })->first();

        if($aportante){
            AsAfiliadoPagador::create(
                [
                    'as_afiliado_id' => $afiliado->id,
                    'as_pagador_id' => $aportante->id,
                    'fecha_vinculacion' => $novedad[12],
                    'nf_ciiu_id' => $actividad->id,
                ]
            );
        }

        $afiliado->as_clasecotizante_id = $clase_cotizante->id;

        $this->guardarAccion($afiliado,$regRespuesta,null,null,'Se agrego relación laboral');
    }

    protected function N07(AsAfiliado $afiliado, $novedad, $regRespuesta){

        $oldValues = [
            'parentesco' => $afiliado->parentesco->codigo,
            'condicion_discapacidad' => $afiliado->condicion_discapacidad->abreviatura,
            'cabfamilia_id' => $afiliado->cabfamilia_id,
            'cabfamilianterior_id' => $afiliado->cabfamilianterior_id
        ];

        $cabeza = $this->getAfiliado($novedad[13],$novedad[14]);
        $tipafiliado = AsTipafiliado::where('codigo_planos',$novedad[15])->first();
        $parentesco = AsParentesco::where('codigo', $novedad[16])->first();
        $condicion = AsCondicionDiscapacidade::where('abreviatura', $novedad[17])->first();

        if(!$tipafiliado){
            throw new \Exception('Tipo de afiliado no valido: '.$novedad[15]);
        }
        if(!$parentesco){
            throw new \Exception('Parentesco no valido: '.$novedad[16]);
        }
        if(!$condicion){
            throw new \Exception('Condicion de discapacidad no valida: '.$novedad[17]);
        }

        $afiliado->cabfamilianterior_id = $afiliado->cabfamilia;
        $afiliado->cabfamilia_id = $cabeza->id;
        $afiliado->as_tipafiliado_id = $tipafiliado->id;
        $afiliado->as_parentesco_id = $parentesco->id;
        $afiliado->as_condicion_discapacidades_id = $condicion->id;

        $afiliado->save();

        $newValues = [
            'parentesco' => $afiliado->parentesco->codigo,
            'condicion_discapacidad' => $afiliado->condicion_discapacidad->abreviatura,
            'cabfamilia_id' => $afiliado->cabfamilia_id,
            'cabfamilianterior_id' => $afiliado->cabfamilianterior_id
        ];

        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);
    }

    protected function N08(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $clase_cotizante = AsClasecotizante::where('codigo', $novedad[13])->first();
        $tipo_documento_aportante = GnTipdocidentidade::where('abreviatura',$this->getTraslateTipoIdentificacionAportante($novedad[14]))->first();
        $actividad = NfCiiu::where('codigo', $novedad[16])->first();
        
        if(!$clase_cotizante){
            throw new \Exception('Calse de cotizante no valida: '.$novedad[13]);
        }
        if(!$tipo_documento_aportante){
            throw new \Exception('Tipo de documento del aportante no valida: '.$novedad[14]);
        }
        if(!$actividad){
            throw new \Exception('Actividad economica no valida: '.$novedad[16]);
        }

        $aportante = AsPagadore::whereHas('tercero', function ($query) use ($novedad, $tipo_documento_aportante){
            $query->where('identificacion', $novedad[15])
                ->where('gn_tipdocidentidad_id', $tipo_documento_aportante->id);
        })->first();

        if($aportante){
            AsAfiliadoPagador::create(
                [
                    'as_afiliado_id' => $afiliado->id,
                    'as_pagador_id' => $aportante->id,
                    'fecha_vinculacion' => $novedad[12],
                    'nf_ciiu_id' => $actividad->id,
                ]
            );
        }

        $afiliado->as_clasecotizante_id = $clase_cotizante->id;

        $this->guardarAccion($afiliado,$regRespuesta,null,null,'Se agrego relación laboral');

    }


    protected function N09(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'estado' => $afiliado->estado_afiliado->codigo
        ];

        $nuevoEstado = AsEstadoafiliado::where('codigo', 'AF')->first();
        
        $afiliado->estado = $nuevoEstado->id;
        $afiliado->save();

        $estado_retirado = AsEstadoafiliado::where('codigo', 'RE')->first();


        $newValues = [
            'estado' => 'AF: Desafiliado por fallecimiento',
        ];
        if($afiliado->beneficiarios){
            $newValues['beneficiarios_retirados'] = $afiliado->beneficiarios->count();
            foreach ($afiliado->beneficiarios as $beneficiario) {
                $beneficiario->estado = $estado_retirado->id;
                $beneficiario->save();
            }
        }

        $this->guardarAccion($afiliado,$regRespuesta,$oldValues,$newValues);
    }

    protected function N10(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $clase_cotizante = AsClasecotizante::where('codigo', $novedad[18])->first();
        $tipo_documento_aportante = GnTipdocidentidade::where('abreviatura',$this->getTraslateTipoIdentificacionAportante($novedad[13]))->first();
        $actividad = NfCiiu::where('codigo', $novedad[17])->first();

        if(!$clase_cotizante){
            throw new \Exception('Clase de cotizante no valida: '.$novedad[16]);
        }
        if(!$tipo_documento_aportante){
            throw new \Exception('Tipo de documento del aportante no valida: '.$novedad[18]);
        }
        if(!$actividad){
            throw new \Exception('Actividad economica no valida: '.$novedad[13]);
        }

        $aportante = AsPagadore::whereHas('tercero', function ($query) use ($novedad, $tipo_documento_aportante){
            $query->where('identificacion', $novedad[14])
                ->where('gn_tipdocidentidad_id', $tipo_documento_aportante->id);
        })->first();

        if(!$aportante){
            throw new \Exception('No existe el aportante no valida: '.$novedad[18].' '.$novedad[14]);
        }

        $relacion_laboral = AsAfiliadoPagador::where('as_afiliado_id',$afiliado->id)
                                                ->where('as_pagador_id', $aportante->id)
                                                ->where('estado', 'Activo')->first();

        $oldValues = [
            'as_clasecotizante_id' => $relacion_laboral->as_clasecotizante_id,
            'nf_ciiu_id' => $relacion_laboral->nf_ciiu_id,
            'fecha_vinculacion' => $relacion_laboral->fecha_vinculacion
        ];

        if($relacion_laboral){
            $relacion_laboral->as_clasecotizante_id = $clase_cotizante->id;
            $relacion_laboral->nf_ciiu_id = $actividad->id;
            $relacion_laboral->fecha_vinculacion = ToolsTrait::homologarFecha($novedad[16]);
            $relacion_laboral->save();
        }

        $newValues = [
            'as_clasecotizante_id' => $relacion_laboral->as_clasecotizante_id,
            'nf_ciiu_id' => $relacion_laboral->nf_ciiu_id,
            'fecha_vinculacion' => $relacion_laboral->fecha_vinculacion
        ];

        $this->guardarAccion($afiliado,$regRespuesta,$oldValues,$newValues,'Se actualizó la relación laboral');

    }

    protected function N11(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $this->novedadSinAlgoritmo($novedad);
    }

    protected function N12(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'as_condicion_discapacidades_id' => $afiliado->as_condicion_discapacidades_id,
        ];

        $afiliado->as_condicion_discapacidades_id = $novedad[13];
        $afiliado->save();

        $newValues = [
            'as_condicion_discapacidades_id' => $afiliado->as_condicion_discapacidades_id,
        ];

        $this->guardarAccion($afiliado,$regRespuesta,$oldValues, $newValues);
    }

    protected function N13(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $this->novedadSinAlgoritmo($novedad);
    }
    //Implementada
    protected function N14(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'estado' => $afiliado->estado_afiliado->codigo
        ];
        $causal = AsCondterminacione::where('codigo', $novedad[14])->first();
        $nuevoEstado = AsEstadoafiliado::where('codigo', $novedad[13])->first();

        if(!$causal){
            throw new \Exception('Condicion de terminacion No valida: '.$novedad[14]);
        }
        if(!$nuevoEstado){
            throw new \Exception('Estado del afiliado no valida: '.$novedad[13]);
        }

        $afiliado->estado = $nuevoEstado->id;
        $afiliado->save();

        $newValues = [
            'estado' => $afiliado->estado_afiliado->codigo,
            'causal' => $causal->causal
        ];


        $this->guardarAccion($afiliado, $regRespuesta,$oldValues,$newValues);

    }

    protected function N15(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $this->novedadSinAlgoritmo($novedad);
    }


    protected function N16(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'estado' => $afiliado->estado->nombre
        ];

        $afiliado->estado = AsEstadoafiliado::where('codigo', 'AC')->first()->id();
        $afiliado->save();

        $newValues = [
            'estado' => $afiliado->estado->nombre
        ];

        $this->guardarAccion($afiliado, $regRespuesta,$oldValues, $newValues);
    }

    //Implementada
    protected function N17(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'sexo' => $afiliado->sexo->abreviatura,
        ];

        $sexo = GnSexo::where('abreviatura', $novedad[13])->first();

        if(!$sexo){
            throw new \Exception('Genero del afiliado no valida: '.$novedad[13]);
        }

        $afiliado->gn_sexo_id = $sexo->id;
        $afiliado->save();

        $newValues = [
            'sexo' => $novedad[13],
        ];

        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);

    }
    //Implementada
    protected function N19(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'zona' => $afiliado->zona->nombre,
        ];

        $zona_afiliado = GnZona::where('codigo', $novedad[13])->first();
        
        if(!$zona_afiliado){
            throw new \Exception('Codigo de la zona no valida: '.$novedad[13]);
        }

        $afiliado->gn_zona_id = $zona_afiliado->id;
        $afiliado->save();

        $newValues = [
            'zona' => $novedad[13],
        ];

        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);
    }
    //Implementada
    protected function N20(AsAfiliado $afiliado, $novedad, $regRespuesta){

        $oldValues = [
          'nivel_sisben' => $afiliado->nivel_sisben
        ];

        $afiliado->nivel_sisben = $novedad[13];
        $afiliado->save();

        $newValues = [
            'nivel_sisben' => $novedad[13]
        ];

        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);

    }
    //Implementada
    protected function N21(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'poblacion_especial' => $afiliado->poblacion_especial->nombre,
        ];

        $poblespecial = AsPobespeciale::where('codigo', $novedad[13])->first();

        if(!$poblespecial){
            throw new \Exception('Poblacion especial no valida: '.$novedad[13]);
        }

        $afiliado->as_pobespeciale_id = $poblespecial->id;
        $afiliado->save();

        $newValues = [
            'poblacion_especial' => $poblespecial->nombre,
        ];

        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);

    }

    ///Falta N24
    protected function N25(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $this->pushMonitor('No se ha implementado la función para procesar esta novedad: ' .$novedad[11],$this->proceso,'error--text');

    }

    protected function N31(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'estado' => $afiliado->estado_afiliado->codigo
        ];

        $nuevoEstado = AsEstadoafiliado::where('codigo', 'AC')->first();
        $afiliado->estado = $nuevoEstado->id;
        $afiliado->save();


        $newValues = [
            'estado' => 'AF: Desafiliado por fallecimiento',
        ];

        $this->guardarAccion($afiliado,$regRespuesta,$oldValues,$newValues);
    }

    protected function N32(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'parentesco' => $afiliado->parentesco->codigo,
            'condicion_discapacidad' => $afiliado->condicion_discapacidad->abreviatura,
            'cabfamilia_id' => $afiliado->cabfamilia_id,
            'cabfamilianterior_id' => $afiliado->cabfamilianterior_id
        ];

        $cabeza = $this->getAfiliado($novedad[13], $novedad[14]);
        $parentesco = AsParentesco::where('codigo',$novedad[15])->first();
        $condicion_discapacidad = AsCondicionDiscapacidade::where('abreviatura',$novedad[16])->first();

        if(!$parentesco){
            throw new \Exception('Parentesco no valido no valida: '.$novedad[15]);
        }
        if(!$condicion_discapacidad){
            throw new \Exception('Condicion de discapacidad no valida: '.$novedad[16]);
        }

        $afiliado->as_parentesco_id = $parentesco->id;
        $afiliado->as_condicion_discapacidades_id = $condicion_discapacidad->id;
        $afiliado->cabfamilianterior_id = $afiliado->cabfamila_id;
        $afiliado->cabfamilia_id = $cabeza->id;

        $afiliado->save();

        $newValues = [
            'parentesco' => $afiliado->parentesco->codigo,
            'condicion_discapacidad' => $afiliado->condicion_discapacidad->abreviatura,
            'cabfamilia_id' => $afiliado->cabfamilia_id,
            'cabfamilianterior_id' => $afiliado->cabfamilianterior_id
        ];

        $this->guardarAccion($afiliado, $regRespuesta, $oldValues, $newValues);

    }

    protected function N33(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'estado' => $afiliado->estado->nombre
        ];

        $causal = AsCondterminacione::where('codigo', $novedad[13]);

        if(!$causal){
            throw new \Exception('Causal de terminacion no valida: '.$novedad[13]);
        }

        $afiliado->estado = AsEstadoafiliado::where('codigo', 'RE')->first()->id();
        $afiliado->fecha_retiro = ToolsTrait::homologarFecha($novedad[14]);
        $afiliado->as_condterminacione_id = $causal->id;
        $afiliado->save();

        $newValues = [
            'estado' => $afiliado->estado->nombre,
            'as_condterminacione_id' => $afiliado->as_condterminacione_id,
            'fecha_retiro' =>$afiliado->fecha_retiro
        ];

        $this->guardarAccion($afiliado, $regRespuesta,$oldValues, $newValues);
    }

    protected function N35(AsAfiliado $afiliado, $novedad, $regRespuesta){
        $oldValues = [
            'fecha_afiliacion' => $afiliado->fecha_afiliacion
        ];

        $afiliado->fecha_afiliacion = ToolsTrait::homologarFecha($novedad[15]);
        $afiliado->save();

        $newValues = [
            'fecha_afiliacion' => $afiliado->fecha_afiliacion
        ];

        $this->guardarAccion($afiliado,$regRespuesta, $oldValues, $newValues);
    }
}