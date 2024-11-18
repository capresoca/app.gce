<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22/01/2019
 * Time: 1:58 PM
 */

namespace App\Capresoca\CuentasMedicas;


use App\Capresoca\AfiliadoTrait;
use App\Models\CuentasMedicas\CmFacinternacion;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\Log;

class CargarHospitalizacionesFromRips extends RipsCsvToBD
{
    use ServiciosRipsTrait;
    use AfiliadoTrait;

    protected $radicado;

    public function __construct(RsRipsRadicado $rips, CmRadicado $radicado)
    {
        $this->radicado = $radicado;

        parent::__construct($rips, 'AH');

    }

    public function store()
    {
        CmFacinternacion::insert($this->getData());
    }

    public function getData()
    {
        $hospitalizacions = array();

        foreach ($this->data as $rowHospitalizacion) {
            try{
                $factura = $this->getFactura($rowHospitalizacion[0]);

                try{

                    $afiliado = $this->getAfiliado($rowHospitalizacion[2],$rowHospitalizacion[3]);
                    $afiliado_id = $afiliado->id;

                }catch (\Exception $exception){
                    $afiliado_id = null;
                }
                try{

                    $autorizacion = $this->getAutorizacion($rowHospitalizacion[6]);
                    $autorizacion_id = $autorizacion->id;
                }catch (\Exception $exception){
                    $autorizacion_id = null;
                }

                $hospitalizacion = [
                    'cm_factura_id'     => $factura->id,
                    'as_afiliado_id'    => $afiliado_id,
                    'tipo'              => 'Hospitalizacion',
                    'tipo_documento'    => $rowHospitalizacion[2],
                    'documento'         => $rowHospitalizacion[3],
                    'via_ingreso'       => $this->getValueForEnum('cm_facinternaciones','via_ingreso',$rowHospitalizacion[4]),
                    'fecha_ingreso'     => ToolsTrait::homologarFecha($rowHospitalizacion[5]),
                    'au_autorizacion_id'=> $autorizacion_id,
                    'causa_externa'     => $this->getValueForEnum('cm_facinternaciones','causa_externa',$rowHospitalizacion[8]),
                    'fecha_egreso'      => ToolsTrait::homologarFecha($rowHospitalizacion[17]),
                    'cie10_ingreso'     => $rowHospitalizacion[9],
                    'cie10_salida'      => $rowHospitalizacion[10],
                    'cie10_relacionado1'=> $rowHospitalizacion[11],
                    'cie10_relacionado2'=> $rowHospitalizacion[12],
                    'cie10_relacionado3'=> $rowHospitalizacion[13],
                    'complicacion'      => $rowHospitalizacion[14],
                    'estado_salida'     => $this->getValueForEnum('cm_facinternaciones','estado_salida',$rowHospitalizacion[15]),
                    'cie10_muerte'      => $rowHospitalizacion[16]
                ];

                array_push($hospitalizacions, $hospitalizacion);

            }catch (\Exception $exception){
                Log::error($exception);
                continue;
            }
        }
        return $hospitalizacions;
    }

}