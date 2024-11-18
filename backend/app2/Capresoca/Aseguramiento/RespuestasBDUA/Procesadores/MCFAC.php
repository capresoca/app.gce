<?php


namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsAfitramite;
use App\Models\Aseguramiento\AsTramite;
use App\Traits\ToolsTrait;

class MCFAC extends Procesador implements ProcesadorCSVInterface
{
    public function procesar()
    {

        foreach ($this->data as $afiliacion)
        {
            try{
                $afiliado = $this->getAfiliado($afiliacion[3],$afiliacion[4]);

                if($afiliado){
                    $this->crearAfitramiteFake($afiliacion, $afiliado);
                }

                $this->pushMonitor('Se proceso afiliacion: '.$afiliacion[1].$afiliacion[2],$this->proceso,'console-success');
            }catch (\Exception $e){
                $this->pushMonitor($e->getMessage(),$this->proceso,'console-error');
            }
        }

        $this->pushMonitor('Se completo el proceso MSFAC con exito',$this->proceso,'console-success');
    }

    private function crearAfitramiteFake($afiliacion, $afiliado)
    {
        $tramite = AsTramite::create([
            'tipo_tramite' => 'MC',
            'consecutivo' => AsTramite::max('consecutivo') + 1,
            'clase_tramite' => 'Testing',
            'fecha_radicacion' => ToolsTrait::homologarFecha($afiliacion[18]),
            'estado' => 'Radicado',
        ]);

        AsAfitramite::create([
            'as_tramite_id' => $tramite->id,
            'as_afiliado_id' => $afiliado->id,
        ]);
    }
}