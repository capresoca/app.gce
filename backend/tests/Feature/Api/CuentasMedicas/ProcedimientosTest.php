<?php

namespace Tests\Feature\Api\CuentasMedicas;

use App\Capresoca\CuentasMedicas\CargarFacturasFromRips;
use App\Capresoca\CuentasMedicas\CargarHospitalizacionesFromRips;
use App\Capresoca\CuentasMedicas\CargarMedicamentosFromRips;
use App\Capresoca\CuentasMedicas\CargarNacimientosFromRips;
use App\Capresoca\CuentasMedicas\CargarOtrosFromRips;
use App\Capresoca\CuentasMedicas\CargarProcedimientosFromRips;
use App\Capresoca\CuentasMedicas\CargarUrgenciasFromRips;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcedimientosTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $rips = RsRipsRadicado::find(58);
        $radicado = CmRadicado::find(66);

        $cargadorProcedimientos = new CargarMedicamentosFromRips($rips,$radicado);

        $cargadorProcedimientos->store($radicado);
    }
}
