<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22/01/2019
 * Time: 10:30 AM
 */

namespace App\Capresoca\CuentasMedicas;


use App\Capresoca\AfiliadoTrait;
use App\Models\CuentasMedicas\CmFacnacimiento;
use App\Models\CuentasMedicas\CmFacservicio;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\Log;

class CargarNacimientosFromRips extends RipsCsvToBD
{
    use ServiciosRipsTrait;
    use AfiliadoTrait;

    protected $radicado;

    public function __construct(RsRipsRadicado $rips, CmRadicado $radicado)
    {
        $this->radicado = $radicado;

        parent::__construct($rips, 'AN');

    }

    public function store()
    {
        CmFacnacimiento::insert($this->getData());
    }

    public function getData()
    {
        $nacimientos = array();

        foreach ($this->data as $rowNacimiento) {
            try{
                $factura = $this->getFactura($rowNacimiento[0]);

                try{

                    $afiliado = $this->getAfiliado($rowNacimiento[2],$rowNacimiento[3]);

                    $afiliado_id = $afiliado->id;

                }catch (\Exception $exception){
                    $afiliado_id = null;
                }
                try{

                    $autorizacion = $this->getAutorizacion($rowNacimiento[4]);
                    $autorizacion_id = $autorizacion->id;
                }catch (\Exception $exception){
                    $autorizacion_id = null;
                }


                $nacimiento = [
                    'cm_facturas_id'    => $factura->id,
                    'as_afiliados_id'   => $afiliado_id,
                    'fecha_nacimiento'  => ToolsTrait::homologarFecha($rowNacimiento[4]),
                    'edad_gestacional'  => $rowNacimiento[6],
                    'control_prenatal'  => $rowNacimiento[7],
                    'sexo'              => $rowNacimiento[8],
                    'peso'              => $rowNacimiento[9],
                    'diagnostico'       => $rowNacimiento[10],
                ];

                array_push($nacimientos, $nacimiento);

            }catch (\Exception $exception){
                Log::error('No se encontro el cum: '. $rowNacimiento[5]);
                continue;
            }
        }
        return $nacimientos;
    }

}