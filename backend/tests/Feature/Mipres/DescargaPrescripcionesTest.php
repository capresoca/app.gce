<?php

namespace Tests\Feature\Mipres;

use App\Jobs\Mipres\NovedadesPrescripcionFecha;
use App\Jobs\Mipres\PrescripcionesPorFecha;
use App\Jobs\Mipres\TutelasFecha;
use Tests\TestCase;

class DescargaPrescripcionesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDescargaPrescripciones()
    {
        $prescipciones = new PrescripcionesPorFecha('2019-02-12','Subsidiado');

        $this->assertEquals('200',$prescipciones->getStatus());

        $prescipciones->store();
    }

    public function testDescargarNovedades()
    {
        $novedades = new NovedadesPrescripcionFecha('2019-02-12','Subsidiado');

        $this->assertEquals('200',$novedades->getStatus());

        $novedades->store();

    }

    public function testDescargarTutelas()
    {
        $tutelas = new TutelasFecha('2019-02-05','Subsidiado');
        $this->assertEquals('200',$tutelas->getStatus());
        $tutelas->store();
    }
}
