<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/01/2019
 * Time: 6:21 PM
 */

namespace App\Capresoca\CuentasMedicas;


use App\Capresoca\AfiliadoTrait;
use App\Models\CuentasMedicas\CmFacservicio;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use Illuminate\Support\Facades\Log;

class CargarOtrosFromRips extends RipsCsvToBD
{
    use ServiciosRipsTrait;
    use AfiliadoTrait;

    protected $radicado;

    public function __construct(RsRipsRadicado $rips, CmRadicado $radicado)
    {
        $this->radicado = $radicado;

        parent::__construct($rips, 'AT');

    }

    public function store()
    {
        CmFacservicio::insert($this->getData());
    }

    public function getData()
    {
        $otros = array();

        foreach ($this->data as $rowOtro) {
            try{
                $factura = $this->getFactura($rowOtro[0]);
 
                try{

                    $afiliado = $this->getAfiliado($rowOtro[2],$rowOtro[3]);
                    $afiliado_id = $afiliado->id;

                }catch (\Exception $exception){
                    $afiliado_id = null;
                }
                try{

                    $autorizacion = $this->getAutorizacion($rowOtro[4]);
                    $autorizacion_id = $autorizacion->id;
                }catch (\Exception $exception){
                    $autorizacion_id = null;
                }


                $tipos = [
                    1 => 'Materiales',
                    2 => 'Traslados',
                    3 => 'Estancias',
                    4 => 'Honorarios'
                ];

                $otro = [
                    'cm_factura_id'     => $factura->id,
                    'tipo'              => $tipos[$rowOtro[5]],
                    'tipo_documento'    => $rowOtro[2],
                    'documento'         => $rowOtro[3],
                    'codigo'            => $rowOtro[6],
                    'unidad_medida'     => $tipos[$rowOtro[5]],
                    'copago'            => 0,
                    'nombre'            => $rowOtro[7],
                    'cantidad'          => $rowOtro[8],
                    'valor_unitario'    => $rowOtro[9],
                    'fecha_servicio'    => $factura->fecha,
                    'au_autorizacion_id'=> $autorizacion_id,
                    'as_afiliado_id'    => $afiliado_id,
                ];

                array_push($otros, $otro);

            }catch (\Exception $exception){
                Log::error('No se encontro el cum: '. $rowOtro[5]);
                continue;
            }
        }
        return $otros;
    }
}