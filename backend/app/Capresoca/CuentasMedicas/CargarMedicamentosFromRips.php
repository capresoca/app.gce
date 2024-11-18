<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/01/2019
 * Time: 4:41 PM
 */

namespace App\Capresoca\CuentasMedicas;


use App\Capresoca\AfiliadoTrait;
use App\Models\CuentasMedicas\CmFacservicio;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\RedServicios\RsCum;
use App\Models\Riips\RsRipsRadicado;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\Log;

class CargarMedicamentosFromRips extends RipsCsvToBD
{
    use ServiciosRipsTrait;
    use AfiliadoTrait;

    protected $radicado;

    public function __construct(RsRipsRadicado $rips, CmRadicado $radicado)
    {
        $this->radicado = $radicado;

        parent::__construct($rips, 'AM');

    }

    public function store()
    {
        CmFacservicio::insert($this->getData());
    }

    public function getData()
    {
        $medicamentos = array();

        $identificacionBuscada = null;
        $afiliado_id = null;

        foreach ($this->data as $rowMedicamento) {
            try{
                $factura = $this->getFactura($rowMedicamento[0]);
                $cum = $this->getCum($rowMedicamento[5]);

                try{
                    if($identificacionBuscada != $rowMedicamento[2].$rowMedicamento[3]){
                        $afiliado = $this->getAfiliado($rowMedicamento[2],$rowMedicamento[3]);
                        $afiliado_id = $afiliado->id;
                        $identificacionBuscada = $rowMedicamento[2].$rowMedicamento[3];
                    }

                }catch (\Exception $exception){
                    $afiliado_id = null;
                }
                try{

                    $autorizacion = $this->getAutorizacion($rowMedicamento[5]);
                    $autorizacion_id = $autorizacion->id;
                }catch (\Exception $exception){
                    $autorizacion_id = null;
                }


                $tipos = [
                    1 => 'Medicamentos PBS',
                    2 => 'Medicamentos NO PBS'
                ];

                $medicamento = [
                    'cm_factura_id'     => $factura->id,
                    'tipo'              => $tipos[$rowMedicamento[6]],
                    'tipo_documento'    => $rowMedicamento[2],
                    'documento'         => $rowMedicamento[3],
                    'codigo'            => $rowMedicamento[5],
                    'nombre'            => $cum->producto,
                    'unidad_medida'     => $cum->unidad_medida,
                    'cantidad'          => $rowMedicamento[11],
                    'valor_unitario'    => $rowMedicamento[12],
                    'copago'            => 0,
                    'fecha_servicio'    => $factura->fecha,
                    'rs_cum_id'        => $cum->id,
                    'au_autorizacion_id'=> $autorizacion_id,
                    'as_afiliado_id'    => $afiliado_id,
                ];


                array_push($medicamentos, $medicamento);

            }catch (\Exception $exception){
                Log::error('No se encontro el cum: '. $rowMedicamento[5]);
                continue;
            }
        }
        return $medicamentos;
    }

    private function getCum($codigo_cum)
    {

        $explodeConsecutivo = explode('-',$codigo_cum);

        $cum = RsCum::where([
            'expediente_cum'    => (int)$explodeConsecutivo[0],
            'consecutivo'       => isset($explodeConsecutivo[1]) ? (int)$explodeConsecutivo[1] : null

        ])->select('id','unidad_medida','producto')->first();
        if(!$cum) throw new \Exception('No se encontro el cup: '.$codigo_cum);

        return $cum;
    }
}