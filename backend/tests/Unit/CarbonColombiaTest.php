<?php

namespace Tests\Unit;

use App\Traits\CarbonColombia;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarbonColombiaTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $fecha_inicio = CarbonColombia::create(2018,11,19);

        $fecha_inicio->addBussinessDays(8);

        self::assertEquals('2018-11-29',$fecha_inicio->format('Y-m-d'));

        $fecha2 = CarbonColombia::create(2019,04,16);
        $fecha2->addBussinessDays(5);

        self::assertEquals('2019-04-25', $fecha2->format('Y-m-d'));
    }

    public function testDiffInBussinessDay()
    {
        $fecha_mayor = CarbonColombia::parse('2019-02-28');

        $fecha_menor = CarbonColombia::parse('2019-03-07');

        $diasAbiles = $fecha_menor->diffInBussinessDays($fecha_mayor,false);

        $this->assertEquals(-5,$diasAbiles);
    }

    public function testBussinessDayOfMonth()
    {

        $fecha = CarbonColombia::parse('2019-02-05');

        $diaHabilMes = $fecha->bussinessDayOfMonth();

        $this->assertEquals($diaHabilMes,3);
    }
}
