<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Capresoca\Contratos\RenderMinuta;
use App\Http\Requests\ContratacionEstatal\MinutaRequest;
use App\Http\Resources\RedServicios\ContratoResource;
use App\Http\Resources\RedServicios\MinutaResource;
use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\ContratacionEstatal\CeProconminutaPrCdps;
use App\Models\RedServicios\RsPlanescobertura;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Barryvdh\DomPDF\Facade as PDF;

class ProconminutaController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return MinutaResource::collection(CeProconminuta::estaActivo()->with('entidad', 'proceso_contractual', 'tipo_contrato')->pimp()->orderBy('fecha_contrato', 'desc')->paginate(Input::get('per_page')));
        }

        return Resource::collection(CeProconminuta::estaActivo()->with('entidad', 'proceso_contractual', 'tipo_contrato')->pimp()->get());
    }

    public function store(MinutaRequest $request)
    {
        try {
            $data = $this->validaSiPrdcspIsEmpty($request);

            $contrato = new CeProconminuta();
            $contrato->fill($request->all());
            $contrato->save();
            $contrato->load($contrato->allRelations());

            foreach ($data['prcdps'] as $key => $datum) {
                $contrato->prcdps()->create([
                    'pr_cdp_id' => $datum['pr_cdp_id'],
                    'valor_descontado' => $datum['valor_descontado']
                ]);
            }

            RenderMinuta::replaceTags($contrato);
            if ($request->estado === 'Confirmado') {
                $this->setPlantillaMinuta($contrato);
                $this->savePlanesCobertura($contrato);
            }

            return (new Resource($contrato))->response()->setStatusCode(201);
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ],500);
        }
    }

    public function show(CeProconminuta $minuta)
    {
        $minuta->load($minuta->allRelations());
        RenderMinuta::replaceTags($minuta);

        return new Resource($minuta);
    }

    public function update(MinutaRequest $request, CeProconminuta $minuta)
    {
        try {
            $data = $this->validaSiPrdcspIsEmpty($request);

            $minuta->update($request->all());
            $minuta->load($minuta->allRelations());

            $minuta->prcdps()->delete();
            $minuta->prcdps()->createMany($data['prcdps']);

            $estadoActual = $minuta->estado;


            if ($estadoActual != 'Confirmado' && $request->estado === 'Confirmado') {
                $this->setPlantillaMinuta($minuta);
                $this->savePlanesCobertura($minuta);
            }

            return (new Resource($minuta))->response()->setStatusCode(202);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ],500);
        }
    }

    public function addMinuta(Request $request, CeProconminuta $minuta)
    {
        if ($request->hasFile('archivo_minuta')) {
            $archivo = ArchivoTrait::subirArchivo($request->file('archivo_minuta'), 'ProcesoContractual/Minutas');
            $minuta->minuta_firmada = $archivo->id;
            $minuta->save();
        }

        return response('', 202);
    }

    public function destroy(CeProconminuta $minuta)
    {

        $minuta->delete();

        return response(null, 202);
    }

    private function setPlantillaMinuta($minuta)
    {
        $minuta->minuta = $minuta->tipo_contrato->plantillaminuta;
        $minuta->minuta_original = $minuta->tipo_contrato->plantillaminuta;
        $minuta->save();
    }

    public function getMinutaPDF()
    {
        $minuta = CeProconminuta::find(Input::get('id'));

        if (Input::get('html')) {
            return view('dompdf.minuta', ['minuta' => $minuta]);
        }

        $pdf = PDF::loadView('dompdf.minuta', ['minuta' => $minuta]);
        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream('Minuta '.$minuta->numero_contrato);
    }

    /**
     * @param $minuta
     * @return \Illuminate\Http\JsonResponse
     * @author Jorge Eduardo Hernández Oropeza 27/03/2020
     */

    public function savePlanesCobertura(CeProconminuta $minuta)
    {
        try {
            $nombre = $minuta->numero_contrato . ' ' . $minuta->tipo_contrato['nombre'];

            $datos = [
                [
                    'estado' => 'Legalizado',
                    'ce_proconminuta_id' => $minuta->id,
                    'regimen_atendido' => 1,
                    'portabilidad' => ($minuta->ce_tipocontrato_id === 1 ? 1 : 0),
                    'nombre' => $nombre . ' Contributivo',
                ],
                [
                    'estado' => 'Legalizado',
                    'ce_proconminuta_id' => $minuta->id,
                    'regimen_atendido' => 2,
                    'nombre' => $nombre . ' Subsidiado',
                ],
            ];

            foreach ($datos as $key => $dato) {
                RsPlanescobertura::create($dato);
            }

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage(),
                'msg' => $exception,
            ]);
        }
    }

    /**
     * @param MinutaRequest $request
     * @return array
     * @throws \Exception
     */
    public function validaSiPrdcspIsEmpty(MinutaRequest $request): array
    {
        $data = $request->toArray();
        if ($data['prcdps'] === [] || (count($data['prcdps']) < 0)) {
            throw new \Exception('Debe existir almenos un registro de CDP.');
        }
        return $data;
    }
}


