<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/10/2018
 * Time: 6:15 PM
 */

namespace App\Capresoca\Aseguramiento;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsEstadoafiliado;
use App\Models\Aseguramiento\AudiAfiliados;
use App\Models\General\GnMunicipio;
use App\Models\General\GnTipdocidentidade;
use App\Traits\ToolsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AditorAfiliados
{
    public function auditar()
    {

        $tipos_id = GnTipdocidentidade::all();
        $municipios = GnMunicipio::all();

        $id_estados = [
            'AC' => 1,
            'NC' => 2,
            'SU' => 5,
        ];

        $documentos_extranjeros = ['PA','CD','SC','CE','PE'];


        $per_page = 100;
        $lote = 32;
        $audiafiliados = AudiAfiliados::whereIn('estado',['AC','NC','SU'])->skip(0)->take($per_page)->get();


        while ($audiafiliados->count()){
            Log::info('Procesando el lote ' . ((($lote-1)*$per_page) + 1 . ' - ' . $lote * $per_page));
            foreach ($audiafiliados as $audiafiliado) {
                try{

                    $fecha_nacimiento = $this->homologarFecha($audiafiliado->fecha_nacimiento);
                    $fecha_afiliacion = $this->homologarFecha($audiafiliado->fecha_afiliacion_sgsss);
                    AsAfiliado::updateOrCreate(
                        [
                            'gn_tipdocidentidad_id' => $tipos_id->firstWhere('abreviatura',$audiafiliado->tipo_documento)->id,
                            'identificacion' => $audiafiliado->numero_documento
                        ],
                        [
                            'apellido1' => $audiafiliado->primer_apellido,
                            'apellido2' => $audiafiliado->segundo_apellido,
                            'nombre1' => $audiafiliado->primer_nombre,
                            'nombre2' => $audiafiliado->segundo_nombre,
                            'fecha_nacimiento' => $fecha_nacimiento,
                            'estado' => $id_estados[$audiafiliado->estado],
                            'ciudadania' => in_array($audiafiliado->tipo_documento,$documentos_extranjeros) ? 'Extranjero' : 'Nacional',
                            'direccion' => $audiafiliado->direccion,
                            'celular' => $audiafiliado->telefono,
                            'gn_sexo_id' => $audiafiliado->genero === 'M' ? 2 : 1,
                            'gn_municipio_id' => $municipios->firstWhere('codigo',$audiafiliado->departamento.$audiafiliado->municipio)->id,
                            'as_regimen_id' => 2,
                            'nf_ciuu_id' => 1604,
                            'ficha_sisben' => $audiafiliado->numero_ficha,
                            'nivel_sisben' => $audiafiliado->nivel_sisben,
                            'fecha_sgsss' => $fecha_afiliacion,
                            'fecha_afiliacion' => $this->homologarFecha($audiafiliado->fecha_afil_entidad),
                            'gn_zona_id' => $audiafiliado->zona === 'U' ? 1 : 2

                        ]
                    );
                }catch (\Exception $exception)
                {
                    Log::error($exception->getMessage(). ' - ' .$exception->getLine().' - id:' . $audiafiliado->id);
                }
            }
            $audiafiliados = AudiAfiliados::whereIn('estado',['AC','NC','SU'])->skip($per_page*$lote)->take($per_page)->get();
            $lote++;
        }

    }

    private function homologarFecha($fecha)
    {
        if(!$fecha){
            return;
        }
        $fecha_f = explode(' ',$fecha);
        $fecha_f = explode('/', $fecha_f[0]);
        $fecha_carbon = Carbon::create($fecha_f[2],$fecha_f[1],$fecha_f[0])->toDateString();
        return $fecha_carbon;
    }
}