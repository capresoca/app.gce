<?php

namespace Tests\Unit\Recaudo;

use App\Capresoca\RecaudosSOI\CuerpoPlanilla;
use App\Capresoca\RecaudosSOI\EncabezadoPlanilla;
use App\Capresoca\RecaudosSOI\PersistirPlanilla;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class parseEncabezadoPlanillaTest extends TestCase
{


    public function testParse()
    {
        $file = __DIR__ . '/file2.TXT';

        $planilla = new PersistirPlanilla($file);
        $result = $planilla->handle();

        $this->assertEquals(true,$result);
    }
}