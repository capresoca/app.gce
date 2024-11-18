<?php

namespace App\Jobs\Mipres;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class DescargarNovedades implements ShouldQueue
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


        DB::beginTransaction();

        $novedadesPrescripcionContr = new NovedadesPrescripcionFecha($this->fecha,'Contributivo');
        $novedadesPrescripcionContr->store();

        $novedadesPrescripcionSubs = new NovedadesPrescripcionFecha($this->fecha,'Subsidiado');
        $novedadesPrescripcionSubs->store();

        $novedadesTutelasContr = new NovedadesTutelasFecha($this->fecha,'Contributivo');
        $novedadesTutelasContr->store();

        $novedadesTutelasSubs = new NovedadesTutelasFecha($this->fecha,'Subsidiado');
        $novedadesTutelasSubs->store();


        DB::commit();

    }
}
