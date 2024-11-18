<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Repositories\Niif\ComdiarioRespository;
use App\Http\Resources\CuentasMedicas\RadicadoResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\CuentasMedicas\CmFactura;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\Riips\RsRipsRadicado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class CuentasCapitadasController extends Controller
{

    public function index() {}

    public function store(Request $request) {}

    public function show($id) {}

    public function update(Request $request, $id)
    {
        try {
            $factura = CmFactura::find($id);
            if (!is_null($factura->feha_repcapita)) {
                abort(422, "La factura #$factura->consecutivo ya fue actualizada.");
            }
            $radicado = CmRadicado::with('facturas')->find($factura['cm_radicado_id']);
            if (isset($request->rs_contrato_id)) {
                $radicado->rs_contrato_id = $request->rs_contrato_id;
            }
            $factura->consecutivo = $request->factura['consecutivo'];
            $factura->valor_neto = $request->factura['valor_neto'];
            $factura->valor_comision = $request->factura['valor_comision'];
            $factura->valor_descuentos = $request->factura['valor_descuentos'];
            $factura->valor_compartido = $request->factura['valor_compartido'];
            $factura->feha_repcapita = Carbon::now()->toDateTimeString();
            $factura->gs_usuario_capita = auth()->user()->id;
            $factura->save();
            $radicado->save();
            $comdiarioRepository = new ComdiarioRespository();
            if ($radicado->nombre_estado === 'Radicado') {
                $valorNetoTotal = $request->factura['valor_neto'];
                $radicado['valorTotalNeto'] = (double) $valorNetoTotal;
                $comdiarioRepository->createComcuentaradicada($radicado);
            }

            return response()->json([
                    'data' => new RadicadoResource($radicado),
                    'msg' => 'Se realiza exitosa modificación a la cuenta capitada.'
                ])->setStatusCode(202);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy($id) {}

    public function getFacturaCapita($id)
    {
        try {
            $factura = DB::selectOne("SELECT a.id,
                                                    a.consecutivo,
                                                    a.valor_compartido,
                                                    a.valor_comision,
                                                    a.valor_descuentos,
                                                    a.valor_neto,
                                                    a.cm_radicado_id
		                                    FROM cm_facturas AS a WHERE a.id = '{$id}'");

            $radicado = DB::selectOne("SELECT a.id,
                                                    a.consecutivo,
                                                    a.rs_contrato_id,
                                                    a.rs_rip_radicado_id,
                                                    a.rs_entidad_id
                                              FROM cm_radicados AS a WHERE a.id = '{$factura->cm_radicado_id}'");
            $rip = DB::selectOne("SELECT a.id, a.tipo_facturacion FROM rs_rips_radicados AS a WHERE a.id = '{$radicado->rs_rip_radicado_id}'");

            if ($rip->tipo_facturacion !== "Capita") {
                throw new \Exception("El RIP de la cuenta # $radicado->consecutivo No es CAPITA.");
            }

            $data1 = json_decode(json_encode($factura),true);
            $data2 = json_decode(json_encode($radicado),true);

            return response()->json([
                'radicado' => new Resource($data2),
                'factura' => new Resource($data1)
            ]);
        } catch (\Exception $exception) {
            return response()->json(['errors' => $exception->getMessage()])->setStatusCode(500);
        }
    }
}
