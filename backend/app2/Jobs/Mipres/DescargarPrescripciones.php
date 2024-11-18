<?php

namespace App\Jobs\Mipres;

use App\Mipres\MpConfig;
use App\Models\Aseguramiento\AsRegimene;
use App\Models\General\GnEmpresa;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DescargarPrescripciones implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $regimen;
    protected $fecha;

    public $tries = 10;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fecha=null)
    {
        $this->fecha = !$fecha ? today()->toDateString():$fecha;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        new GeneradorTokenProveedor();

        $prescipciones = new PrescripcionesPorFecha($this->fecha,'Contributivo');
        $prescipciones->store();

        $prescripSubsidiado = new PrescripcionesPorFecha($this->fecha,'Subsidiado');
        $prescripSubsidiado->store();

        $tutelasSubs = new TutelasFecha($this->fecha,'Subsidiado');
        $tutelasSubs->store();

        $tutelasContr = New TutelasFecha($this->fecha,'Contributivo');
        $tutelasContr->store();

        $dS = new Direccionamiento();
        $dS->getByFecha($this->fecha,'Subsidiado');
        $dS->store();

        $dC = new Direccionamiento();
        $dC->getByFecha($this->fecha,'Contributivo');
        $dC->store();

        $reportesContr = New ReporteEntregaXFecha($this->fecha,'Contributivo');
        $reportesContr->store();

        $reportesSubs = New ReporteEntregaXFecha($this->fecha,'Subsidiado');
        $reportesSubs->store();

        $hj = new HistoricoJuntas();
        $hj->descargar();

    }
}
