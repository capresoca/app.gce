<?php


namespace App\Capresoca\Compensacion;


use App\Models\Compensacion\CpArchivosaporte;

class ReprocesarAportes
{
    public static function run($id_inicial = 16)
    {
        $pilas = CpArchivosaporte::where('id','>',$id_inicial)->get();

        foreach ($pilas as $pila) {
            $procesador = new ProcesadorAportes($pila);
            $procesador->procesar();
            print ('Procesado '.$pila->id);
        }
    }
}