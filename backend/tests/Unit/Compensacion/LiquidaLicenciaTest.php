<?php

namespace Tests\Unit\Compensacion;

use App\Capresoca\Compensacion\EstadoRelacionLaboral;
use App\Models\AtencionUsuario\AuIncapacidade;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LiquidaLicenciaTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDiasPagadosGestacion()
    {
        $incapacidad = AuIncapacidade::find(45);

        $estado = new EstadoRelacionLaboral($incapacidad);

        dd($estado->liquidacion());

        //$this->assertEquals(232,$dias);

    }


    public function testAportesGestacion()
    {
        $incapacidad = AuIncapacidade::find(32);
        $relacion_laboral = $incapacidad->relacion_laboral;
        $estado = new EstadoRelacionLaboral($relacion_laboral);
        $aportes = $estado->aportes_gestacion($incapacidad);

        $aportes->count();

        $this->assertEquals(8,$aportes->count());

    }
}
