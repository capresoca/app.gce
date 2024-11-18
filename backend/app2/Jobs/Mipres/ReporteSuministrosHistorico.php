<?php


namespace App\Jobs\Mipres;


use App\Models\Mipres\MpReporteentrega;
use App\Models\Mipres\MpReporteSuministro;
use Illuminate\Support\Facades\DB;

abstract class ReporteSuministrosHistorico
{
    public static function crearReportes()
    {
        try{

            MarcarDireccionamientosUltimaEntrega::run();
            MpReporteentrega::where('NoLote','string')->update(['NoLote' => null]);


            MpReporteentrega::join('mp_direccionamientos','mp_direccionamientos.suministro_id','mp_reporteentregas.suministro_id')
                ->select([
                    'mp_reporteentregas.suministro_id',
                    'mp_direccionamientos.UltEntrega',
                    DB::raw("if(mp_direccionamientos.CantTotAEntregar < mp_reporteentregas.CantTotEntregada,0,1) as EntregaCompleta"),
                    'mp_reporteentregas.CausaNoentrega',
                    'mp_reporteentregas.NoPrescripcion as NoPrescripcionAsociada',
                    'mp_reporteentregas.ConTec as ConTecAsociada',
                    'mp_reporteentregas.CantTotEntregada',
                    'mp_reporteentregas.NoLote',
                    'mp_reporteentregas.ValorEntregado',
                    DB::raw('0 as reportado')
                ])->chunk(2000,function ($reportes){
                    MpReporteSuministro::insert($reportes->toArray());
                });

        }catch (\Exception $e){
            print $e->getTrace();
        }

    }

    public static function reportar()
    {
        $suministros = MpReporteSuministro::whereReportado(0)->orderBy('suministro_id','desc')->get();

        foreach ($suministros as $suministro) {
            try{

                $regimen = $suministro->prescripcion ? $suministro->prescripcion->regimen : 'subsidiado';

                $reporteSumi = new ReporteSuministro($suministro,$regimen);
                $reporte = $reporteSumi->send();
                $suministro->reporte_id = $reporte[0]['IdSuministro'];
                $suministro->reportado = 1;
                $suministro->save();

            }catch (\Exception $e){
                print($e->getMessage());
                print ("No se pudo reportar ID: ".$suministro->suministro_id);
            }
        }

    }

    public static function reportarContributivo()
    {
        $suministros = MpReporteSuministro::whereReportado(0)->where('suministro_id', '<', 21282997)->orderBy('suministro_id','desc')->get();

        foreach ($suministros as $suministro) {
            try{

                $regimen = 'contributivo';

                $reporteSumi = new ReporteSuministro($suministro,$regimen);
                $reporte = $reporteSumi->send();
                $suministro->reporte_id = $reporte[0]['IdSuministro'];
                $suministro->reportado = 1;
                $suministro->save();

            }catch (\Exception $e){
                print($e->getMessage());
                print ("No se pudo reportar ID: ".$suministro->suministro_id);
            }
        }

    }
}

