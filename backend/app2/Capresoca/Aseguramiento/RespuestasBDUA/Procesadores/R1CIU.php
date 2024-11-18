<?php


namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Niif\NfCiiu;

class R1CIU extends Procesador implements ProcesadorCSVInterface
{

    public function procesar()
    {
        foreach ($this->data as $datum) {

            try{
                $afiliado = $this->getAfiliado($datum[1], $datum[2]);

                $ciiu = NfCiiu::whereCodigo($datum[32])->firstOrFail();

                $afiliado->nf_ciiu_id = $ciiu->id;
                $afiliado->save();
                $this->pushMonitor('Se cambio el estado del tramite traslado contributivo a VALIDADO y se aplicaron los cambios correspondientes: ', $this->proceso,'console-success');


            } catch (\Exception $e){
                $this->pushMonitor($e->getMessage().' Line:'.$e->getLine(),$this->proceso,'error--text');
                continue;
            }

        }
    }
}