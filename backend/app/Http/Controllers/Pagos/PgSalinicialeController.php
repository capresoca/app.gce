<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Repositories\Pagos\AnticipoRespository;
use App\Http\Repositories\Pagos\CxpRespository;
use App\Http\Requests\Pagos\PgSalinicialeRequest;
use App\Http\Resources\Pagos\PgSalinicialeResource;
use App\Models\Pagos\PgSaliniciale;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class PgSalinicialeController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            $pg_saliniciale = PgSaliniciale::with('niif','proveedor.tercero', 'proveedor.niif')->pimp()->orderBy('consecutivo', 'desc')->paginate(Input::get('per_page'));
            return PgSalinicialeResource::collection($pg_saliniciale);
        }
        return PgSalinicialeResource::collection(PgSaliniciale::with('niif','proveedor.tercero', 'proveedor.niif')->pimp()->orderBy('consecutivo', 'desc')->get());

    }

    public function store(PgSalinicialeRequest $request)
    {
        $pg_saliniciale = PgSaliniciale::create($request->all());
        $pg_saliniciale->load('niif', 'proveedor.tercero', 'proveedor.niif');
        $this->createAnticipoOrCXP($request, $pg_saliniciale);
        return new PgSalinicialeResource($pg_saliniciale);
    }

    public function show(PgSaliniciale $pg_saliniciale)
    {
        $pg_saliniciale->load('niif', 'proveedor.tercero', 'proveedor.niif');
        return new PgSalinicialeResource($pg_saliniciale);
    }

    public function update(PgSalinicialeRequest $request, PgSaliniciale $pg_saliniciale)
    {
        try {
            $pg_saliniciale->update($request->all());
            $pg_saliniciale->load('niif', 'proveedor.tercero', 'proveedor.niif');
            $this->createAnticipoOrCXP($request, $pg_saliniciale);
            return response()->json([
                'message' => 'El saldo fue actualizado correctamente.',
                'saliniciale' => new PgSalinicialeResource($pg_saliniciale)
            ], 200);

        } catch (ValidationException $e)
        {
            return response()->json([
                'message' => 'Los datos enviados son invalidos',
                'errors' => $e->validator
            ],422);
        } catch (\Exception $e)
        {
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'Error en el servidor'
            ], 500);
        }
    }

    public function createAnticipoOrCXP($request, $saldo_inicial)
    {
        if ($request->estado === 'Confirmado' && $request->tipo === 'Anticipo') {
            $anticipoRepository = new AnticipoRespository();
            $anticipoRepository->createArrayAnticipo($saldo_inicial->id, $request,1);
        }

        if ($request->estado === 'Confirmado' && $request->tipo === 'CXP') {
            $cxpRepository = new CxpRespository();
            $cxpFromSaldoIniciale = [
                'fecha_causacion' => $request->fecha,
                'pg_proveedore_id' => $request->pg_proveedore_id,
                'fecha_factura' => $request->fecha_documento,
                'cxp_plazo' => $request->cxp_plazo,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'no_factura'=> $request->no_documento,
                'gn_tercero_id' => $request->proveedor['tercero']['id'],
                'nf_niif_id' => $request->niif['id'],
                'modulo' => 'Saldo Inicial',
                'estado' => $request->estado,
                'observaciones' => 'Saldo Inicial No. ' . str_pad($saldo_inicial->consecutivo,6,"0",STR_PAD_LEFT),
                'detalles' => $this->detallesCxp($request)
            ];

            $cxpRepository->validar($cxpFromSaldoIniciale);
            $cxpRepository->guardar($cxpFromSaldoIniciale);
        }

    }

    public function detallesCxp($saldo_inicial) {
        $detalles = [];

        array_push($detalles, [
            'nf_niif_id' => $saldo_inicial->niif ? $saldo_inicial->niif['id'] : null,
            'gn_tercero_id' => $saldo_inicial->proveedor['tercero']['id'],
            'naturaleza' => 1,
            'valor' => (double) $saldo_inicial->valor
        ]);

        return $detalles;
    }
}
