<?php

namespace Tests\Unit\Recaudo\Historico;

use App\Capresoca\RecaudosSOI\Historico\ClasificadorArchivoAIUnificado;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistoricoTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $procesador = new ClasificadorArchivoAIUnificado('C:\HISTORICO_OPERADOR_INFORMACION\\ACHHISTORICO\\PREHISTORICO\\');

        $procesador->handle();
    }
}
