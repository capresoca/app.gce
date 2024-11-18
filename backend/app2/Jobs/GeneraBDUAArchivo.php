<?php

namespace App\Jobs;

use App\Capresoca\Aseguramiento\GeneradorArchivosBDUA;
use App\Models\Aseguramiento\AsBduaproceso;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class GeneraBDUAArchivo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $proceso;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AsBduaproceso $proceso)
    {
        $this->proceso = $proceso;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{

            new GeneradorArchivosBDUA($this->proceso);
            Log::info('ejecutado GeneraBDUAArchivo');
        }
        catch (\Exception $e){
            Log::error($e);
        }
    }
}
