<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 18/01/2019
 * Time: 4:43 PM
 */

namespace App\Capresoca\CuentasMedicas;

use App\Models\CuentasMedicas\CmFactura;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use App\Traits\CuentasMedicasTrazaTrait;
use App\Traits\ToolsTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CargarFacturasFromRips extends RipsCsvToBD
{
    public function __construct(RsRipsRadicado $rips)
    {
        parent::__construct($rips, 'AF');
    }

    public function store(CmRadicado $radicado)
    {
        CmFactura::insert($this->getData($radicado->id));

        if (($radicado->tipo_facturacion === 'Capita') && (! is_null($radicado->rs_contrato_id))) {
            $factura = $radicado->facturas()->first();
            CuentasMedicasTrazaTrait::registrarServices($factura,$radicado->rs_contrato_id);
        }

    }

    public function getData($radicado_id = null)
    {
        $invoices = [];
        $collections = new Collection($this->data);
        $bill = null;

        foreach ($collections as $key => $rowInvoice) {
            if ($this->invoiceExist($rowInvoice[4], $rowInvoice[0]) === (true)) {
                continue; // return $invoices = 'Existe entre una a varias facturas que ya se encuentran registradas.';
            }

            $bill = $this->getRow($rowInvoice, $radicado_id);

            array_push($invoices, $bill);
        }

        return $invoices;
    }

    private function getRow($rowFactura, $radicado_id)
    {
        $data = [
            'cm_radicado_id' => $radicado_id,
            'consecutivo' => $rowFactura[4],
            'fecha' => ToolsTrait::homologarFecha($rowFactura[5]),
            'no_contrato' => $rowFactura[10],
            'plan_beneficio' => $rowFactura[11],
            'valor_compartido' => (double) $rowFactura[13],
            'valor_comision' => (double) $rowFactura[14],
            'valor_descuentos' => (double) $rowFactura[15],
            'valor_neto' => (double) $rowFactura[16],
            'estado' => 'Radicada',
        ];

        Log::info('data', $data);

        return $data;
    }

    /**
     * @param string $consecutive
     * @param string $habilitacion
     * @return bool
     */
    private function invoiceExist(string $consecutive, string $habilitacion)
    {
        $exist = DB::select("SELECT count(*) AS total FROM cm_facturas AS fac
                                LEFT JOIN cm_radicados AS rad ON fac.cm_radicado_id = rad.id
                                LEFT JOIN rs_rips_radicados AS rips ON rips.id = rad.rs_rip_radicado_id
                                LEFT JOIN rs_entidades AS ent ON rad.rs_entidad_id = ent.id
                                WHERE fac.consecutivo = '{$consecutive}' AND ent.cod_habilitacion = '{$habilitacion}' AND rips.tipo_facturacion = 'Evento'
                                LIMIT 1")[0]->total;

        return ($exist !== 0) ? (true) : (false);
    }
}

