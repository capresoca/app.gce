<?php


namespace App\Jobs\ProcesosBDUA;


use App\Capresoca\Aseguramiento\ProcesosBDUA\ProcesadorS1;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcesarArchivosBDUA
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $archivos_bdua;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($archivosBdua)
    {
        $this->archivos_bdua = $archivosBdua;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $procesador = new ProcesadorS1($this->archivos_bdua);
        $procesador->procesar();

    }



}