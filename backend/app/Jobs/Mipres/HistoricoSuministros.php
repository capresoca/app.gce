<?php


namespace App\Jobs\Mipres;


use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class HistoricoSuministros
{
    /**
     * HistoricoSuministros constructor.
     * @param null $desde
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct($direcccionamientosDesde = null,$reportesDesde = null)
    {
        if(!$direcccionamientosDesde){
            $direcccionamientosDesde = today()->subMonth()->toDateString();
        }
        $fecha = Carbon::parse($direcccionamientosDesde);
        while (today()->greaterThan($fecha)){
                $fechaString = clone $fecha;
                $fechaString = $fechaString->toDateString();
                print ("Descargando direcionamientos subsidiado de: ".$fechaString);
                $d = new Direccionamiento();
                $d->getByFecha($fechaString,'Subsidiado');
                print ("  Descargado  ");
                $d->store();
                print ("Guardado \n");
                print ("Descargando direcionamientos contributivo de: ".$fechaString);
                $d = new Direccionamiento();
                $d->getByFecha($fechaString,'Contributivo');
                print ("Descargado  ");
                $d->store();
                print ("Guardado \n");
                $fecha->addDay();
        }

        if(!$reportesDesde){
            $reportesDesde = today()->subMonth()->toDateString();
        }
        $fecha = Carbon::parse($reportesDesde);

        while (today()->greaterThan($fecha)){
            $fechaString = clone $fecha;
            $fechaString = $fechaString->toDateString();
            print ('Descargando suministros de: '.$fechaString."\n");
            $r = new ReporteEntregaXFecha($fechaString,'Subsidiado');
            $r->store();

            $r = new ReporteEntregaXFecha($fechaString,'Contributivo');
            $r->store();
            print ('Descargados suministros de: '.$fechaString."\n");
            $fecha->addDay();

        }
    }
}