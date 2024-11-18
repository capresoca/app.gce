<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Controllers\Controller;
use App\Http\Requests\CuentasMedicas\FacServiciosGlosasRequest;
use App\Http\Requests\CuentasMedicas\FacturasRequest;
use App\Http\Resources\CuentasMedicas\FacServiciosGlosasResource;
use App\Models\CuentasMedicas\CmFacservicio;
use App\Models\CuentasMedicas\CmFactura;
use App\Models\CuentasMedicas\CmGlosa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Validation\ValidationException;

class FacServiciosGlosasController extends Controller
{

    public function index(){}

    public function store(FacServiciosGlosasRequest $request){}

    public function show($id){}

    public function update(FacServiciosGlosasRequest $request, $id)
    {
        try {
            $facservicio = CmFacservicio::find($id);
            $facservicio->fill($request->all());
            $facservicio->save();
            $facservicio->load('glosas.auditor', 'glosas.glosa', 'glosas.factura', 'afiliado', 'autorizacion', 'cum', 'cups', 'factura');
            return response()->json([
                'data' => new FacServiciosGlosasResource($facservicio)
            ], 202);
        } catch (ValidationException $validationException)  {
            return response()->json([
                'error' => $validationException,
                'msg' => $validationException->getMessage()
            ], 422);
        }catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($glosa)
    {
        try {
            $glosado = CmGlosa::where('id',$glosa)->first();
            $idFacservicioTemp = $glosado['cm_facservicio_id'];
            $ideFacturaTemp = $glosado['cm_factura_id'];
            if ($idFacservicioTemp !== null) {
                $facservicio = CmFacservicio::with('glosas')
                    ->select('id', 'avalado')
                    ->whereId($idFacservicioTemp)->first();
                if (!!$facservicio->glosas) {
                    $facservicio->avalado = null;
                }
                $facservicio->save();
            }
            if ($ideFacturaTemp !== null) {
                $this->deleteGlosasFacturaServicio($glosado, $ideFacturaTemp);
            }

            $glosado->delete();
//            $idFacservicioTemp = null;
            return response()->json(
                [
                    'message' => 'La glosa se ha removido del detalle de la factura.'
                ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    // Eliminar Glosas de facservicio que son de factura
    public function deleteGlosasFacturaServicio ($glosado, $ideFacturaTemp) {
        $servicios = CmFacservicio::with('glosas')->where('cm_factura_id', $ideFacturaTemp)->get();
        foreach ($servicios as $servicio) {
            $glosas = $servicio->glosas;
            foreach ($glosas as $glosa) {
                if ((!is_null($glosa['of_facservicio']) && ($glosa['of_facservicio'] !== 2))
                    && ($glosa->cm_manglosa_id === $glosado['cm_manglosa_id'])) {
                    $glosa->delete();
                }
            }
            if (!!$glosas) $servicio->avalado = null;
//            if ($servicio->avalado !== 1) $servicio->avalado = null;
            $servicio->save();
        }
    }
    
    // Guardar Glosa de Facservicio 3143559102
    public function saveGlosa (CmFacservicio $facservicio, FacServiciosGlosasRequest $request)
    {
        try {
            //$factura = CmFactura::with('radicado')->find($facservicio['cm_factura_id']);
            //$modalidad = RsRipsRadicado::select('id', 'tipo_facturacion')->where('id', $factura->radicado()->first()->rs_rip_radicado_id)->first()->tipo_facturacion;
            //if ($modalidad === 'Capita') {
            //    $valor_total = $factura->valor_capita;
            //} else {
            //}
            $valor_total = $this->getValotTotalXServicio($facservicio)  - ($facservicio['copago']);
            if ($request->tipo === 'Porcentaje') {
                $porcentaje = $request['valor_glosa'];
                $valor_glosa = round(($valor_total * ($porcentaje / 100)),2);
            }
            $glosa = CmGlosa::create([
                'cm_facservicio_id' => $facservicio->id,
                'cm_manglosa_id' => $request['cm_manglosa_id'],
                'tipo' => $request['tipo'],
                'valor_glosa' => ($request['tipo'] !== 'Porcentaje')
                    ? ($request['tipo'] !== 'Valor total' ? $request['valor_glosa'] : $valor_total) : (double) ($valor_glosa),
                'observacion' => $request['observacion'],
                'of_facservicio' => $request['of_facservicio']
            ]);
            $glosa->load('glosa', 'factura');
            $servicio = CmFacservicio::find($request->cm_facservicio_id);
            $servicio->avalado = 0;
            $servicio->save();
            return response()->json(
                [
                    'data' => new FacServiciosGlosasResource($glosa)
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    // Guardado de Glosa de Factura
    public function saveGlosaFactura (CmFactura $factura, FacturasRequest $request)
    {
        try {
            if ($factura->modalidad === 'Capita' && $request->tipo === 'Porcentaje') {
                $porcentaje = $request['valor_glosa'];
                $valor_glosa = round(($factura->valor_neto * ($porcentaje / 100)),2);
            }

            $glosa = $factura->glosas()->create($request->all());
            if ($factura->modalidad === 'Capita') {
                $glosa->valor_glosa = $valor_glosa;
                $glosa->save();
            }
            $glosa->load('glosa', 'factura');
            if ($factura->modalidad === 'Evento') {
                $this->saveFacserviciosofFactura($request);
            }
            return  response()->json(
                [
                    'data' => new Resource($glosa)
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function saveFacserviciosofFactura ($factura)
    {
        $requesAlls = CmFacservicio::with('glosas')->where('cm_factura_id',$factura->cm_factura_id)->get();
        $observacion = $factura->observacion;

        foreach ($requesAlls as $item) {
            $valorTotal = ($item->cantidad * $item->valor_unitario) - ($item->copago);
            $valor = (count($item->glosas) > 0) ? ($item->valor_servicio_glosado + $valorTotal) : ($valorTotal);
            $siono_suma = ($valor <= $valorTotal) ? 1 : 2;

            $item->glosas()->create(
                [
                    'fecha_glosa' => Carbon::now()->format('Y-m-d'),
                    'cm_factura_id' => null,
                    'cm_facservicio_id' => $item->id,
                    'cm_manglosa_id' => $factura->cm_manglosa_id,
                    'valor_glosa' => (double) $valorTotal,
                    'observacion' => "$observacion - Glosado general de la Factura.",
                    'of_facservicio' => $siono_suma
                ]
            );
            $item->avalado = 0;
            $item->save();
        }
    }
    
    // Guardados Masivos
    
    public function saveAltocostoFacservicio (Request $request)
    {
        try {
            $altosCostos = $request->altos_costos;
            $servicios = $request->facservicios;
            $costos = [];
            foreach ($servicios as $servicio) {
                $facservicio = CmFacservicio::with('altosCostos')->whereId($servicio['id'])->first();
                $facservicio->altosCostos()->delete();
                foreach ($altosCostos as $item) {
                    $facservicio->altosCostos()->create(
                        [
                            'as_tipaltocosto_id' => $item
                        ]
                    );
                }
                $costos = $facservicio->altosCostos;
            }
            return response()->json([
                'msg' => 'Se ha guardado éxitosamente.',
                'altosCostos' => new Resource($costos)
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => $exception->getMessage()
            ],500);
        }
    }

    public function saveMassive (Request $request)
    {
        try {
            $servicios = $request->facservicios;
            $metodo = $request->item;
            if ($metodo === 'avalado') {
                $this->$metodo($request, $servicios);
            } elseif ($metodo === 'glosa') {
                $this->$metodo($servicios, $request);
            } else {
                foreach ($servicios as $servicio) {
                    if (($metodo !== ' ') || ($metodo !== 'avalado') || ($metodo !== 'glosa') ) {
                        $this->$metodo($servicio, $request);
                    }
                }
            }

            return response(new Resource(['data' => ['ok' => true]]),202);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception,
                'errors' => $exception->getMessage()
            ], 500);
        }
    }


    /**
     * @param $servicio
     * @param $request
     * @throws \Exception
     */
    public function recobro ($servicio, $request)
    {
        try {
            $facservicio = CmFacservicio::whereId($servicio)->first();
            $facservicio->recobro = $request->seleccionado;
            $facservicio->save();
        }catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $servicio
     * @param $request
     * @throws \Exception
     */
    public function capita ($servicio, $request) {
        try {
            $facservicio = CmFacservicio::whereId($servicio)->first();
            $facservicio->capita = ($facservicio->capita === 0) ? 1 : 0;
            $facservicio->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $servicios
     * @param $request
     * @throws \Exception
     */
    public function glosa ($servicios, $request) {
        try {
            $valServices = array();
            if (is_null($request->glosa['of_facservicio'])) {
                foreach ($servicios as $key => $servicio) {
                    //$factura = CmFactura::with('radicado')->find($facservicio['cm_factura_id']);
                    //$modalidad = RsRipsRadicado::select('id', 'tipo_facturacion')->where('id', $factura->radicado()->first()->rs_rip_radicado_id)->first()->tipo_facturacion;
                    //if ($modalidad === 'Capita') {
                    //    $valor_total = $facservicio->factura()->first()->valor_capita;
                    //} else {
                    //    $valor_total = $this->getValotTotalXServicio($facservicio)  - ($facservicio['copago']);
                    //}
                    $facservicio = CmFacservicio::with('glosas')->find($servicio);
                    $valor_total = $this->getValotTotalXServicio($facservicio) - ($facservicio['copago']);
                    $valor_glosa = $this->getValorTotXPorcentaje($request, $valor_total);
                    $valor = $this->getCalSiNoGlosas($request, $facservicio, $valor_total, $valor_glosa);
                    if (($valor > $valor_total)) {
                        array_push($valServices, $servicio);
                    }
                }
            }
            if (count($valServices) > 0) {
                throw new \Exception('Uno o varios servicios no se pueden glosar porque el valor total glosado es mayor que el valor total.');
            } else {
                foreach ($servicios as $servicio) {
                    $facservicio = CmFacservicio::with('glosas')->find($servicio);
                    $facservicio->avalado = 0;
                    $facservicio->save();
                    //$factura = CmFactura::with('radicado')->find($facservicio['cm_factura_id']);
                    //$modalidad = RsRipsRadicado::select('id', 'tipo_facturacion')->where('id', $factura->radicado()->first()->rs_rip_radicado_id)->first()->tipo_facturacion;
                    //if ($modalidad === 'Capita') {
                    //    $valor_total = $facservicio->factura()->first()->valor_capita;
                    //} else {
                    //    $valor_total = $this->getValotTotalXServicio($facservicio)  - ($facservicio['copago']);
                    //}
                    $valor_total = $this->getValotTotalXServicio($facservicio)  - ($facservicio['copago']);
                    $valor_glosa = $this->getValorTotXPorcentaje($request, $valor_total);
                    $facservicio->glosas()->create(
                        [
                            'fecha_glosa' => Carbon::now()->format('Y-m-d'),
                            'cm_factura_id' => null,
                            'cm_manglosa_id' => $request->glosa['cm_manglosa_id'],
                            'valor_glosa' => (double) $this->getValorXTipo($request, $valor_total, $valor_glosa),
                            'observacion' => $request->glosa['observacion'],
                            'of_facservicio' => $request->glosa['of_facservicio']
                        ]
                    );
                }
            }
            // Un Mierdero Pendiente
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $request
     * @param $items
     * @throws \Exception
     */
    public function avalado ($request, $items) {
        try {
            $serviceSinGlosas = [];
            foreach ($items as $key => $item) {
                $servicio = CmFacservicio::sinGlosa()->whereId($item)->first();
                array_push($serviceSinGlosas, $servicio);
            }
            $avalados = collect($serviceSinGlosas)->whereIn('avalado',1,true);
            foreach ($items as $key => $item) {
                $servicie = CmFacservicio::sinGlosa()->whereId($item)->first();
                if (($avalados->count() === count($items))) {
                    $servicie->avalado = null;
                }
                if (($avalados->count() !== count($items)) && (($servicie->avalado === null) || ($servicie->avalado === 1))) {
                    $servicie->avalado = 1;
                }
                $servicie->save();
            }

        }catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $request
     * @param $valor_total
     * @param float $valor_glosa
     * @return float
     */
    private function getValorXTipo($request, $valor_total, float $valor_glosa): float
    {
        return ($request->glosa['tipo'] !== 'Porcentaje' ? ($request->glosa['tipo'] !== 'Valor total' ? $request->glosa['valor_glosa'] : $valor_total) : $valor_glosa);
    }

    /**
     * @param $facservicio
     * @return float|int
     */
    private function getValotTotalXServicio($facservicio)
    {
        return ($facservicio->cantidad * $facservicio->valor_unitario);
    }

    /**
     * @param $request
     * @param $valor_total
     * @return float
     */
    private function getValorTotXPorcentaje($request, $valor_total): float
    {
        $valor_glosa = 0;

        if ($request->glosa['tipo'] === 'Porcentaje') {
            $porcentaje = $request->glosa['valor_glosa'];
            $valor_glosa = round(($valor_total * ($porcentaje / 100)), 2);
        }

        return $valor_glosa;
    }

    /**
     * @param $request
     * @param $facservicio
     * @param $valor_total
     * @param float $valor_glosa
     * @return float
     */
    private function getCalSiNoGlosas($request, $facservicio, $valor_total, float $valor_glosa): float
    {
        return (count($facservicio->glosas) > 0)
            ? ($facservicio->valor_servicio_glosado + $this->getValorXTipo($request, $valor_total, $valor_glosa))
            : $this->getValorXTipo($request, $valor_total, $valor_glosa);
    }
}
