<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19/01/2019
 * Time: 9:00 AM
 */

namespace App\Capresoca\CuentasMedicas;

use App\Capresoca\AfiliadoTrait;
use App\Models\CuentasMedicas\CmFacservicio;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\RedServicios\RsCups;
use App\Models\Riips\RsRipsRadicado;
use App\Traits\EnumsTrait;
use App\Traits\ToolsTrait;

class CargarConsultasFromRips extends RipsCsvToBD
{
    protected $radicado;

    use ServiciosRipsTrait;
    use AfiliadoTrait;

    public function __construct(RsRipsRadicado $rips, CmRadicado $radicado)
    {
        $this->radicado = $radicado;
        parent::__construct($rips, 'AC');
    }

    public function store()
    {
        //CmFacservicio::insert($this->getData());
		

        foreach (array_chunk($this->getData(),1000) as $t)
        {
            CmFacservicio::insert($t);
        }
        		
    }

    public function getData()
    {
        $consultas = [];
        foreach ($this->data as $rowConsulta) {
            try {
                $factura = $this->getFactura($rowConsulta[0]);

                $cup = $this->getCup($rowConsulta[6]);

                try {
                    $afiliado = $this->getAfiliado($rowConsulta[2], $rowConsulta[3]);
                    $afiliado_id = $afiliado->id;
                } catch (\Exception $exception) {
                    $afiliado_id = null;
                }

                try {
                    $autorizacion = $this->getAutorizacion($rowConsulta[5]);
                    $autorizacion_id = $autorizacion->id;
                } catch (\Exception $exception) {
                    $autorizacion_id = null;
                }

                $consulta = [
                    'cm_factura_id' => $factura->id,
                    'tipo' => 'Consultas',
                    'tipo_documento' => $rowConsulta[2],
                    'documento' => $rowConsulta[3],
                    'codigo' => $rowConsulta[6],
                    'nombre' => $cup->descripcion,
                    'unidad_medida' => 'Consulta',
                    'cantidad' => 1,
                    'valor_unitario' => $rowConsulta[14],
                    'copago' => $rowConsulta[15],
                    'fecha_servicio' => ToolsTrait::homologarFecha($rowConsulta[4]),
                    'finalidad_consulta' => $this->getValueForEnum('cm_facservicios', 'finalidad_consulta', $rowConsulta[7]),
                    'causa_externa' => $this->getValueForEnum('cm_facservicios', 'causa_externa', $rowConsulta[8]),
                    'tipo_diagnostico' => $this->getValueForEnum('cm_facservicios', 'tipo_diagnostico', $rowConsulta[13]),
                    'rs_cups_id' => $cup->id,
                    'au_autorizacion_id' => $autorizacion_id,
                    'as_afiliado_id' => $afiliado_id,
                    'cie10_principal' => $rowConsulta[9],
                    'cie10_relacionado1' => $rowConsulta[10],
                    'cie10_relacionado2' => $rowConsulta[11],
                    'cie10_relacionado3' => $rowConsulta[12],
                ];

                array_push($consultas, $consulta);
            } catch (\Exception $exception) {
                continue;
            }
        }

        return $consultas;
    }
}
