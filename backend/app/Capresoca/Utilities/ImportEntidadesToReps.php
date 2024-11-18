<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/03/2019
 * Time: 3:21 PM
 */

namespace App\Capresoca\Utilities;


use App\Models\Niif\GnTercero;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsEntidadesBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportEntidadesToReps
{
    public function  importar()
    {
        $entidadesReps = RsEntidadesBase::select(DB::raw(
            "rs_entidades_bases.*,nits_nit as identificacion,nombre_prestador as razon_social,
            (case tienNombre when 'Instituciones - IPS' then 1 when 'Profesional Independiente' then 6 when 'Transporte Especial de Pacientes' then 4 else 5 end) as rs_tipentidade_id,
            rs_entidades_bases.codigo as cod_habilitacion, email as correo_electronico_sede, telefono as telefono_sede,
            direccion as direccion_sede, if(sede_principal = 'SI', 'Principal','Sede') as tipo_locacion,
            gerente as replegal, 'Baja' as complejidad,
            12 as gn_tipdocidentidad_id, gn_municipios.id as gn_munexpedicion_id,
            direccion,telefono as celular, email as correo_electronico, gn_municipios.id as gn_municipio_id,gn_municipios.id as gn_municipiosede_id,
            'Hacer Retencion' as tipo_retencion,
            if(tienNombre = 'Instituciones - IPS' and naturaleza = 'Pública','Empresa Estatal','Régimen Común') as tipo_contribuyente,
            if(clase_persona = 'JURIDICO','Jurídica','Natural') as tipo_persona,
            'Contratista' as tipo_tercero,
            0 as estado,
            0 as verificado,
            if(naturaleza = 'Pública','Publica',naturaleza) as naturaleza,
            naturaleza as naturaleza_juridica,
            1 as ica  
        "))->join('gn_municipios','gn_municipios.nombre','rs_entidades_bases.municipio')->where('rs_entidades_bases.id','>',42772);


        foreach ($entidadesReps->cursor() as $entidadReps){
            try{

                if(RsEntidade::whereCodHabilitacion($entidadReps->codigo)->first()) continue;

                $entidadReps = $entidadReps->toArray();
                $tercero = GnTercero::firstOrCreate([
                    'gn_tipdocidentidad_id' => $entidadReps['gn_tipdocidentidad_id'],
                    'identificacion' => $entidadReps['identificacion']
                ],$entidadReps);

                $entidad = new RsEntidade($entidadReps);
                $entidad->gn_tercero_id = $tercero->id;

                $entidad->save();
            }catch (\Exception $e){
                Log::error($e);
                continue;
            }
        }

        return 'Se importaron todas las entidades del reps';
    }
}