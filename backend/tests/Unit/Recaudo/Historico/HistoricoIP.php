<?php

namespace Tests\Unit\Recaudo\Historico;

use App\Capresoca\RecaudosSOI\Historico\ClasificadorIP;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistoricoIP extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $procesador = new ClasificadorIP('C:\HISTORICO_OPERADOR_INFORMACION\\ACHHISTORICO\\HISTORICOACH\\');

        $procesador->handle();
    }
}
