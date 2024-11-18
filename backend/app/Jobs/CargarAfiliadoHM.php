<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CargarAfiliadoHM implements ShouldQueue
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
        try{

        DB::connection()->getPdo()
            ->exec("
                LOAD DATA LOCAL INFILE '{$this->path}'
                INTO TABLE afiliados_healthmanager
                FIELDS TERMINATED BY ','
                (
                  `tipo_documento`,`numero_documento`,`primer_apellido`,`segundo_apellido`,`primer_nombre`,
                    `segundo_nombre`,`fecha_nacimiento`,`genero`,`departamento`,`municipio`,
                    `zona`,`fecha_afil_entidad`,`nivel_sisben`,`grupo_poblacional`,`modalidad_subsidio`,`tipo_afiliado`,
                    `parentesco`,`condicion_mayor_edad`,`fecha_afiliacion_sgsss`,`pertenencia_etnica`,`estado`,
                    `codigo_entidad`,`fecha_carnetizacion`,`nivel_priorizacion`,`estado_civil`,`discapacidad`,
                    `fecha_insercion`,`usuario_insercion`,`fecha_modificacion`,	`usuario_modificacion`,	`numero_contrato`,	`regimen`,
                    `numero_ficha`,`priorizable`,`priorizado`,`direccion`,`telefono`,`barrio`
                ) ");
        } catch (\Exception $exception){
            Log::info('Error ejecutar DB: '.$exception->getMessage());
        }
    }
}

