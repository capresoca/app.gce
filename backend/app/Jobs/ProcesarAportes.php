<?php

namespace App\Jobs;

use App\Capresoca\Compensacion\ProcesadorAportes;
use App\Capresoca\LeerCsv;
use App\Models\Compensacion\CpArchivosaporte;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProcesarAportes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $archivos_aporte;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CpArchivosaporte $archivosAporte)
    {
        $this->archivos_aporte = $archivosAporte;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

            $procesador = new ProcesadorAportes($this->archivos_aporte);
            $procesador->procesar();

    }


}
