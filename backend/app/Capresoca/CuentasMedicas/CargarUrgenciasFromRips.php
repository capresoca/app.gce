<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22/01/2019
 * Time: 10:50 AM
 */

namespace App\Capresoca\CuentasMedicas;


use App\Capresoca\AfiliadoTrait;
use App\Models\CuentasMedicas\CmFacinternacion;
use App\Models\CuentasMedicas\CmFacservicio;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\Log;

class CargarUrgenciasFromRips extends RipsCsvToBD
{
    use ServiciosRipsTrait;
    use AfiliadoTrait;

    protected $radicado;

    public function __construct(RsRipsRadicado $rips, CmRadicado $radicado)
    {
        $this->radicado = $radicado;

        parent::__construct($rips, 'AU');

    }

    public function store()
    {
        CmFacinternacion::insert($this->getData());
    }

    public function getData()
    {
        $urgencias = array();

        foreach ($this->data as $rowUrgencia) {
            try{
                $factura = $this->getFactura($rowUrgencia[0]);

                try{

                    $afiliado = $this->getAfiliado($rowUrgencia[2],$rowUrgencia[3]);
                    $afiliado_id = $afiliado->id;

                }catch (\Exception $exception){
                    $afiliado_id = null;
                }
                try{

                    $autorizacion = $this->getAutorizacion($rowUrgencia[6]);
                    $autorizacion_id = $autorizacion->id;
                }catch (\Exception $exception){
                    $autorizacion_id = null;
                }


                $urgencia = [
                    'cm_factura_id'     => $factura->id,
                    'as_afiliado_id'    => $afiliado_id,
                    'tipo'              => 'Observacion',
                    'tipo_documento'    => $rowUrgencia[2],
                    'documento'         => $rowUrgencia[3],
                    'fecha_ingreso'     => ToolsTrait::homologarFecha($rowUrgencia[4]),
                    'au_autorizacion_id'=> $autorizacion_id,
                    'causa_externa'     => $this->getValueForEnum('cm_facinternaciones','causa_externa',$rowUrgencia[7]),
                    'fecha_egreso'      => ToolsTrait::homologarFecha($rowUrgencia[15]),
                    'cie10_salida'      => $rowUrgencia[8],
                    'cie10_relacionado1'=> $rowUrgencia[9],
                    'cie10_relacionado2'=> $rowUrgencia[10],
                    'cie10_relacionado3'=> $rowUrgencia[11],
                    'destino_salida'    => $this->getValueForEnum('cm_facinternaciones','destino_salida',$rowUrgencia[12]),
                    'estado_salida'     => $this->getValueForEnum('cm_facinternaciones','estado_salida',$rowUrgencia[13]),
                    'cie10_muerte'      => $rowUrgencia[14]
                ];

                array_push($urgencias, $urgencia);

            }catch (\Exception $exception){
                Log::error($exception);
                continue;
            }
        }
        return $urgencias;
    }

}