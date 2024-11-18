<?php

namespace Tests\Unit\Compensacion;

use App\Compensacion\CpAporte;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovilidadAutomaticaTest extends TestCase
{

    public function testIngresoContributivo()
    {
        $aporte = CpAporte::whereIngreso(1)->first();

        $movilidad = MovilidadAutomatica::paraContributivo($aporte);
    }
}
