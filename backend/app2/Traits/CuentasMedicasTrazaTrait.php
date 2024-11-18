<?php

namespace App\Traits;


use App\Models\CuentasMedicas\CmFacservicio;
use App\Models\CuentasMedicas\CmFactura;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

trait CuentasMedicasTrazaTrait {

    public static function validarSiExistenServicios ($id_plancobertura)
    {
        $sevContratados = DB::select("SELECT b.id AS id_plan_cobertura,
                                                    b.ce_proconminuta_id AS id_minuta,
                                                    a.porcentaje_contratado,
                                                    c.codigo AS codigo_servicio,
                                                    c.nombre AS nombre_servicio,
                                                    b.nombre AS numero_contrato
                                            FROM rs_servcontratados AS a
                                            LEFT JOIN rs_planescoberturas AS b ON b.id = a.rs_contrato_id
                                            LEFT JOIN rs_servicios AS c ON c.id = a.rs_servicio_id
                                            WHERE b.id = '{$id_plancobertura}' AND a.porcentaje_contratado != 0");

        return count($sevContratados) > 0 ? true : false;
    }

    /**
     * @param \App\Models\CuentasMedicas\CmFactura $factura
     * @param int $id_plancobertura
     */
    public static function  registrarServices(CmFactura $factura,int $id_plancobertura)
    {
        $sevContratados = DB::select("SELECT b.id AS id_plan_cobertura,
                                                        b.ce_proconminuta_id AS id_minuta,
                                                        a.porcentaje_contratado,
                                                        c.codigo AS codigo_servicio,
                                                        c.nombre AS nombre_servicio,
                                                        b.nombre AS numero_contrato
                                                FROM rs_servcontratados AS a
                                                LEFT JOIN rs_planescoberturas AS b ON b.id = a.rs_contrato_id
                                                LEFT JOIN rs_servicios AS c ON c.id = a.rs_servicio_id
                                                WHERE b.id = '{$id_plancobertura}' AND a.porcentaje_contratado != 0");



        $datos = json_decode(json_encode($sevContratados), true);

        //$servicios = $factura->servicios()->where('tipo','=','Capita')->get();

        if ($factura->modalidad === 'Capita' && $factura->estado === 'Radicada' && (count($datos) > 0)) {
            foreach ($datos as $dato) {
                $valor_calculado = round($factura->valor_neto * ($dato['porcentaje_contratado']/100),2);
                $servCapita = new CmFacservicio();
                $servCapita->cm_factura_id = $factura->id;
                $servCapita->tipo = 'Capita';
                $servCapita->codigo = $dato['codigo_servicio'];
                $servCapita->nombre = $dato['nombre_servicio'];
                $servCapita->cantidad = 1;
                $servCapita->valor_unitario = $valor_calculado;
                $servCapita->fecha_servicio = Date::now()->format('Y-m-d');
                $servCapita->capita = 1;
                $servCapita->save();
            }
        }
    }
}