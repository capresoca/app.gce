<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19/01/2019
 * Time: 8:40 AM
 */

namespace App\Capresoca\CuentasMedicas;


use App\Capresoca\AfiliadoTrait;
use App\Models\CuentasMedicas\CmFacservicio;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use App\Traits\EnumsTrait;
use App\Traits\ToolsTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CargarProcedimientosFromRips extends RipsCsvToBD
{
    use ServiciosRipsTrait;
    use AfiliadoTrait;

    protected $radicado;

    public function __construct(RsRipsRadicado $rips, CmRadicado $radicado)
    {
        $this->radicado = $radicado;

        parent::__construct($rips, 'AP');
    }

    public function store()
    {
        CmFacservicio::insert($this->getData());
    }

    public function getData()
    {
        $process = array();
        $collections = new Collection($this->data);

        foreach ($collections as $key => $rowProcedimiento) {
            Log::info('dataPro', $rowProcedimiento);
            try{
                $factura = $this->getFactura($rowProcedimiento[0]);
                $cup = $this->getCup($rowProcedimiento[6]);

                try{
                    $afiliado = $this->getAfiliado($rowProcedimiento[2],$rowProcedimiento[3]);
                    $afiliado_id = $afiliado->id;
                }catch (\Exception $exception){
                    $afiliado_id = null;
                }
                try{
                    $autorizacion = $this->getAutorizacion($rowProcedimiento[5]);
                    $autorizacion_id = $autorizacion->id;
                }catch (\Exception $exception){
                    $autorizacion_id = null;
                }

                $formas_cirugia = [
                    'Único o unilateral',
                    'Múltiple o bilateral, misma vía, diferente especialidad',
                    'Múltiple o bilateral, misma vía, igual especialidad',
                    'Múltiple o bilateral diferente vía, diferente especialidad',
                    'Múltiple o bilateral, diferente vía, igual especialidad'
                ];

                $procedure = [
                    'cm_factura_id'     => $factura->id,
                    'tipo'              => $cup->rs_cupsseccion_id === 1 ? 'Procedimientos NO Quirurgicos' : 'Procedimientos NO Quirurgicos',
                    'tipo_documento'    => $rowProcedimiento[2],
                    'documento'         => $rowProcedimiento[3],
                    'codigo'            => $rowProcedimiento[6],
                    'nombre'            => $cup->descripcion,
                    'unidad_medida'     => 'Procedimiento',
                    'cantidad'          => 1,
                    'valor_unitario'    => $rowProcedimiento[14],
                    'copago'            => 0,
                    'fecha_servicio'    => ToolsTrait::homologarFecha($rowProcedimiento[4]),
                    'ambito'            => $this->getValueForEnum('cm_facservicios','ambito',$rowProcedimiento[7]),
                    'finalidad_procedimiento'=> $this->getValueForEnum('cm_facservicios','finalidad_procedimiento',$rowProcedimiento[8]),
                    'personal_atiende'  => $this->getValueForEnum('cm_facservicios','personal_atiende',$rowProcedimiento[9]),
                    'forma_cirugia'     => $rowProcedimiento[13] ? $formas_cirugia[$rowProcedimiento[13]-1] : null,
                    'rs_cups_id'        => $cup->id,
                    'au_autorizacion_id'=> $autorizacion_id,
                    'as_afiliado_id'    => $afiliado_id,
                    'cie10_principal'   => $rowProcedimiento[10],
                    'cie10_relacionado1'=> $rowProcedimiento[11],
                    'complicacion'      => $rowProcedimiento[12]
                ];

                Log::info('procedimiento', $procedure);

                array_push($process, $procedure);

            }catch (\Exception $exception){
                continue;
            }
        }

        return $process;
    }


}