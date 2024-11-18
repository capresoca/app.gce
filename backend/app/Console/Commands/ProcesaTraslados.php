<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2\S1Automatico1;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2\R1Automatico;

class ProcesaTraslados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:procesa_traslados';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar los traslados';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $s1     = new S1Automatico1(NULL);
        $s1->procesaActivacion(NULL);
        
        $r1Auto         = new R1Automatico(NULL);
        $r1Auto->procesaActivacion();
    }
    
    /*private function check_in_range ($fecha_inicio, $fecha_fin, $fecha) {
        return ($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin);
    }*/
}
