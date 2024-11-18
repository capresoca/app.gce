<?php

namespace App\Jobs;

use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsTipbduaarchivo;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProcesarArchivoBDUA implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $tipoArchivo;
    protected $filePath;
    protected $parametros;
    protected $proceso;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AsTipbduaarchivo $tipoArchivo, $filePath, AsBduaproceso $proceso)
    {
        $this->tipoArchivo = $tipoArchivo;
        $this->filePath = $filePath;
        $this->proceso = $proceso;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $procesador = new $this->tipoArchivo->clase_procesador($this->filePath, $this->proceso);
            $procesador->procesar();
        } catch (\Exception $e) {
            Log::info('No se ha podido procesar el archivo: ' . $e->getMessage());
        }
    }
}
