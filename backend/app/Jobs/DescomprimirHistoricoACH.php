<?php

namespace App\Jobs;

use App\Capresoca\RecaudosSOI\Historico\ExtractorZipAportanteDirectory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class DescomprimirHistoricoACH implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $zips;

    public function __construct($localStorageDirectory)
    {
        $this->zips = Storage::disk('local')->allFiles($localStorageDirectory);
    }
    public function handle()
    {
        foreach ($this->zips as $key => $zip){
            $rutaZip = storage_path('app').'/'.$this->zips[$key];
            $ExtractorArchivoI = new ExtractorZipAportanteDirectory($rutaZip);
            $ExtractorArchivoI->run();
        }
    }
}
