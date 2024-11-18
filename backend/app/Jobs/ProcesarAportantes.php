<?php

namespace App\Jobs;

use App\Capresoca\LeerCsv;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsTipaportante;
use App\Models\General\GnMunicipio;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCiiu;
use App\Traits\ToolsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ProcesarAportantes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contents = Storage::get($this->path);
        $reader = Reader::createFromString($contents,'r');

        $data = $reader->getRecords();

        $tipos_id = [
            'NI' => 12,
            'CC' => 1,
            'CD' => 6,
            'TI' => 2
        ];

        $tippersonas = [
            'N' => 'Natural',
            'J' => 'Juridica'
        ];

        $naturalezas_jur = [
            '1' => 'Publica',
            '2' => 'Privada',
            '3' => 'Mixta',
            '4' => 'Organismos multilaterales',
            '5' => 'Entidades de derecho publico no sometidos a la legislacion colombiana'
        ];

        $tipos_aportante = AsTipaportante::all();
        $municipios = GnMunicipio::all();

        foreach ($data as $aportante){
            try{

                $id_aportante = ToolsTrait::quitarEspacios($aportante[2]);

                $actividad = NfCiiu::where('codigo',$aportante[13])->first();
                $municipio = $municipios->firstWhere('codigo', ToolsTrait::quitarEspacios($aportante[12]).ToolsTrait::quitarEspacios($aportante[11]));

                $tercero = GnTercero::updateOrCreate(
                    [
                        'identificacion' => ToolsTrait::quitarEspacios($aportante[2]),
                        'gn_tipdocidentidad_id' => $tipos_id[ToolsTrait::quitarEspacios($aportante[1])]
                    ],
                    [
                        'nombre1' => ToolsTrait::quitarEspacios($aportante[22]),
                        'nombre2' => ToolsTrait::quitarEspacios($aportante[23]),
                        'apellido1' => ToolsTrait::quitarEspacios($aportante[20]),
                        'apellido2' => ToolsTrait::quitarEspacios($aportante[21]),
                        'razon_social' => ToolsTrait::quitarEspacios($aportante[0]),
                        'direccion' => ToolsTrait::quitarEspacios($aportante[10]),
                        'telefono_fijo' => ToolsTrait::quitarEspacios($aportante[14]),
                        'correo_electronico' => strtolower(ToolsTrait::quitarEspacios($aportante[16])),
                        'gn_municipio_id' => $municipio->id,
                        'tipo_persona' => $tippersonas[ToolsTrait::quitarEspacios($aportante[8])],
                        'naturaleza_juridica' => $naturalezas_jur[ToolsTrait::quitarEspacios($aportante[7])],
                        'tipo_tercero' => 'Pagador',
                        'nf_ciiu_id' => $actividad ? $actividad->id : null,
                        'digito_verificacion' => $aportante[3],
                    ]

                );

                $aportante = AsPagadore::updateOrCreate(
                    [
                        'identificacion' => $id_aportante,
                    ],
                    [
                        'razon_social' => $tercero->razon_social,
                        'as_tipaportante_id' => $tipos_aportante->firstWhere('codigo',$aportante[6])->id,
                        'gn_tercero_id' => $tercero->id
                    ]
                );
            }catch (\Exception $e){
                Log::info('No se pudo procesar el registro'. $aportante[0]);
                Log::info('Municipio: '.$aportante[12].$aportante[11]);
                Log::info('Identificacion: '.ToolsTrait::quitarEspacios($aportante[2]).' '.ToolsTrait::quitarEspacios($aportante[1]));
                Log::error($e->getMessage().' '.$e->getLine());
                continue;
            }
        }
    }
}
