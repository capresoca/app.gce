<?php


namespace App\Capresoca\RecaudosSOI\Historico;


use Illuminate\Support\Facades\Storage;

class ClasificadorArchivoAI
{

    protected $zips;

    public function __construct($localStorageDirectory)
    {
        $this->zips = Storage::disk('local')->allFiles($localStorageDirectory);
    }
    public function handle()
    {
        $routesZipsExtrated = [
            'A' => [],
            'I' => []
        ];

        foreach ($this->zips as $key => $zip){
            $rutaZip = storage_path('app').'/'.$this->zips[$key];
            $ExtractorArchivoI = new ExtractorZipAportanteDirectory($rutaZip);
            $archivos = $ExtractorArchivoI->run();
            foreach ($archivos as $tipo => $tipos) {
                foreach ($tipos as $value) {
                    array_push($routesZipsExtrated[$tipo],$value);
                }
            }
        }
    }
}