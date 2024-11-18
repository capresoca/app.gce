<?php

namespace App\Jobs;

use App\Capresoca\CuentasMedicas\CargarConsultasFromRips;
use App\Capresoca\CuentasMedicas\CargarFacturasFromRips;
use App\Capresoca\CuentasMedicas\CargarHospitalizacionesFromRips;
use App\Capresoca\CuentasMedicas\CargarMedicamentosFromRips;
use App\Capresoca\CuentasMedicas\CargarNacimientosFromRips;
use App\Capresoca\CuentasMedicas\CargarOtrosFromRips;
use App\Capresoca\CuentasMedicas\CargarProcedimientosFromRips;
use App\Capresoca\CuentasMedicas\CargarUrgenciasFromRips;
use App\Capresoca\CuentasMedicas\CargarUsuariosFromRips;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcesarRips implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rips;

    protected $radicado;

    /**
     * Create a new job instance.
     *
     * @param RsRipsRadicado $rips
     * @param CmRadicado $radicado
     */
    public function __construct(RsRipsRadicado $rips, CmRadicado $radicado)
    {
        $this->rips = $rips;
        $this->radicado = $radicado;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $cargadorAF = new CargarFacturasFromRips($this->rips);

            $cargadorAF->store($this->radicado);

            try {
                $cargadorAC = new CargarConsultasFromRips($this->rips, $this->radicado);
                $cargadorAC->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AC');
            }

            try {
                $cargadorAP = new CargarProcedimientosFromRips($this->rips, $this->radicado);
                $cargadorAP->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AP');
            }

            try {
                $cargadorAM = new CargarMedicamentosFromRips($this->rips, $this->radicado);
                $cargadorAM->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AM');
            }

            try {
                $cargadorAT = new CargarOtrosFromRips($this->rips, $this->radicado);
                $cargadorAT->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AT');
            }

            try {
                $cargadorAU = new CargarUrgenciasFromRips($this->rips, $this->radicado);
                $cargadorAU->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AU');
            }

            try {
                $cargadorAH = new CargarHospitalizacionesFromRips($this->rips, $this->radicado);
                $cargadorAH->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AH');
            }

            try {
                $cargadorAN = new CargarNacimientosFromRips($this->rips, $this->radicado);
                $cargadorAN->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AN');
            }

            try {
                $cargadorUS = new CargarUsuariosFromRips($this->rips, $this->radicado);
                $cargadorUS->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo US');
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }
}
