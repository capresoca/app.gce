<?php

namespace Tests\Feature\Api\RedServicios;

use App\Capresoca\Aseguramiento\Servicios\AsignaServiciosCSV;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssingServiciosFromCSVTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testValidarAfiliadosExist()
    {
        $servhabilitados = '243,1458,1460';
        $path= base_path('tests/Feature/Api/RedServicios/afiliadosAsignarTestInexistentes.txt');
        $asignaServCSV = new AsignaServiciosCSV($path, $servhabilitados);
        $asignaServCSV->handle();
        $respuesta = $asignaServCSV->getRespuesa();
        $this->assertEquals(1,count($respuesta));
        $this->assertEquals(1,count($respuesta[0]['afiliados']));

    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAsignar()
    {
        $servhabilitados = '243,1458,1460';
        $path= base_path('tests/Feature/Api/RedServicios/afiliadosAsignarTest.txt');
        $asignaServCSV = new AsignaServiciosCSV($path, $servhabilitados);
        $asignaServCSV->handle();
        $respuesta = $asignaServCSV->getRespuesa();
        $this->assertEquals(0,count($respuesta));

    }


}
