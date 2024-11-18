<?php

namespace Tests\Unit;

use App\Traits\ToolsTrait;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DigitoVerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUltimoDigitoCero()
    {
        $digito = ToolsTrait::calcularDigitoVerificacion(9430369);
        $this->assertEquals(1,$digito);
    }
}
