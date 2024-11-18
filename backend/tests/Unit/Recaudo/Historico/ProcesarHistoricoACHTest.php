<?php

namespace Tests\Unit\Recaudo;


use App\Capresoca\RecaudosSOI\Historico\ClasificadorArchivoAI;
use App\Capresoca\RecaudosSOI\Historico\ExtractorZipAportanteDirectory;
use App\Jobs\DescomprimirHistoricoACH;
use App\Jobs\PersistirHistoricoPlanillasFromDirectory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Api\TestCase;

class ProcesarHistoricoACHTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDescomprimir()
    {
        $path = storage_path('app/ACHExtracted/I');
        PersistirHistoricoPlanillasFromDirectory::dispatch($path)->onQueue('archivos');

//        $persi = new PersistirHistoricoPlanillasFromDirectory($path);
//        $persi->handle();

    }
}
