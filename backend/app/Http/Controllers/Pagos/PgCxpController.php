<?php

namespace App\Http\Controllers\Pagos;

use App\Exceptions\ComdiarioException;
use App\Http\Repositories\Niif\ComdiarioRespository;
use App\Http\Repositories\Pagos\CxpRespository;
use App\Http\Repositories\Presupuesto\ObligacioneRepository;
use App\Http\Requests\Pagos\PgCxpRequest;
use App\Http\Resources\Pagos\PgCxpResource;
use App\Http\Resources\Pagos\PgCxpsResource;
use App\Models\Pagos\PgCxp;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\URL;

class PgCxpController extends Controller
{
    public function __construct(CxpRespository $repoCxp)
    {
        $this->repoCxp = $repoCxp;
    }

    public function index()
    {
        if(Input::get('per_page')){
            $pg_cxps = PgCxp::with('cencosto','tercero','proveedor.tercero','niif')
                ->pimp()->orderBy('consecutivo', 'desc')->paginate(Input::get('per_page'));
            return PgCxpsResource::collection($pg_cxps);
        }
        return Resource::collection(PgCxp::with('cencosto','tercero','proveedor.tercero','niif')
            ->pimp()->orderBy('consecutivo', 'desc')->get());
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $cxpRequest = $request->toArray();
            $this->repoCxp->validar($cxpRequest);
            $pg_cxp = $this->repoCxp->guardar($cxpRequest);
            $comdiarioRepository = new ComdiarioRespository();
            $obligacionRepository = new ObligacioneRepository();
            if ($request->estado === 'Confirmado') {
                $comdiarioRepository->createFromCxp($pg_cxp);
                $obligacionRepository->createFromCxp($pg_cxp->id, $request);
            }
            DB::commit();
            return response()->json([
                'message' => 'La cuenta por pagar fue creada con éxito.',
                'cxp' => new PgCxpsResource($pg_cxp)
            ], 201);
        } catch (ValidationException $e)
        {
            DB::rollBack();
            return response()->json([
                'message' => 'Los datos enviados son invalidos',
                'errors' => $e->validator
            ],422);
        } catch (ComdiarioException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar el comprobante',
                'errors' => $e->getMessage(),
                'error' => $e
            ], 428);
        } catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'Error en el servidor',
                'error' => $e
            ], 500);
        }
    }
    public function show(PgCxp $pg_cxp)
    {
        return new PgCxpResource(
            $pg_cxp->load(
                [
                    'cencosto',
                    'tercero',
                    'proveedor.tercero',
                    'proveedor.niif',
                    'niif',
                    'detalles',
                    'detalles.conpago.niif.clascuenta',
                    'detalles.uniapoyo',
                    'detalles.cxp',
                    'detalles.tercero',
                    'detalles.cencosto',
                    'detalles.niif',
                    'detalles.conrtf',
                    'obligacion.detalles.rubro',
                    'obligacion.detalles.tipoGasto',
                    'obligacion.detalles.cdp',
                    'obligacion.detalles.rp',
                    'obligacion.detalles.detalle_rp'
                ]
            )
        );
    }

    public function update(PgCxpRequest $request, PgCxp $pg_cxp)
    {
        try {
            DB::beginTransaction();
            $pg_cxp->update($request->all());
            $comdiarioRepository = new ComdiarioRespository();
            $obligacionRepository = new ObligacioneRepository();
            if ($request->estado === 'Confirmado' && !isset($pg_cxp->obligacion)) {
                $comdiarioRepository->createFromCxp($pg_cxp);
                $obligacionRepository->createFromCxp($pg_cxp->id, $request);
            }
            DB::commit();
            return response()->json([
                'message' => 'La cuenta por pagar fue actualizada con éxito.',
                'cxp' => new PgCxpsResource($pg_cxp)
            ], 202);
        } catch (ValidationException $e)
        {
            DB::rollBack();
            return response()->json([
                'message' => 'Los datos enviados son invalidos',
                'errors' => $e->validator
            ],422);
        } catch (ComdiarioException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar el comprobante',
                'errors' => $e->getMessage(),
            ], 428);
        } catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'Error en el servidor'
            ], 500);
        }
//        $pg_cxp->load(['proveedor.tercero','niif']);
    }

    public function getPDF(PgCxp $pg_cxp){
        try{
            //            return 'algo';
            $pg_cxp->load(['cencosto','tercero','proveedor.tercero','proveedor.niif','niif','detalles','detalles.conpago.niif.clascuenta','detalles.uniapoyo','detalles.cxp','detalles.tercero','detalles.cencosto','detalles.niif','detalles.conrtf']);
            if(Input::get('html')){
                return view('dompdf.pg_cxp');
            }

            $pdf = PDF::loadView('dompdf.cxp', ['pg_cxp' => $pg_cxp]);
            $pdf->setPaper('letter', 'portrait');

            return $pdf->stream('Cuenta por pagar', ['Attachment' => 0]);
        }catch (\Exception $e){
            return response()->json([
                'error' => $e,
                'msg' => 'Se ha presentado un error al imprimir.'
            ]);
        }
    }

    public function firmarCxp () {
        return URL::temporarySignedRoute('pdf_pgCxp', now()->addMinute(1), [Input::get('id')]);
    }
//    public function destroy($id)
//    {
//        //
//    }
}
