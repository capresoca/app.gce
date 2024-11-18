<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Capresoca\Contratos\RenderMinuta;
use App\Http\Requests\ContratacionEstatal\MinutaRequest;
use App\Http\Resources\RedServicios\ContratoResource;
use App\Http\Resources\RedServicios\MinutaResource;
use App\Models\ContratacionEstatal\CeProconminuta;
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
        if(Input::get('per_page')){
            return  MinutaResource::collection(
                CeProconminuta::estaActivo()->with('entidad','proceso_contractual', 'tipo_contrato')->pimp()
                    ->orderBy('fecha_contrato','desc')
                    ->paginate(Input::get('per_page'))
            );
        }


        return Resource::collection(
            CeProconminuta::estaActivo()->with('entidad','proceso_contractual', 'tipo_contrato')->pimp()->get()
        );
    }

    public function store(MinutaRequest $request)
    {
        $contrato = CeProconminuta::create($request->all());

        $contrato->load($contrato->allRelations());
        RenderMinuta::replaceTags($contrato);
        if($request->estado ==='Confirmado'){
            $this->setPlantillaMinuta($contrato);
        }

        return (new Resource($contrato))->response()->setStatusCode(201);
    }

    public function show(CeProconminuta $minuta)
    {
        $minuta->load($minuta->allRelations());
        RenderMinuta::replaceTags($minuta);
        return new Resource($minuta);
    }

    public function update(MinutaRequest $request,CeProconminuta $minuta)
    {
        $minuta->update($request->all());
        $minuta->load($minuta->allRelations());

        $estadoActual = $minuta->estado;

        if ($estadoActual != 'Confirmado' && $request->estado === 'Confirmado') {
            $this->setPlantillaMinuta($minuta);
        }

        return (new Resource($minuta))->response()->setStatusCode(202);
    }

    public function addMinuta(Request $request, CeProconminuta $minuta)
    {
        if($request->hasFile('archivo_minuta')){
            $archivo = ArchivoTrait::subirArchivo($request->file('archivo_minuta'), 'ProcesoContractual/Minutas');
            $minuta->minuta_firmada = $archivo->id;
            $minuta->save();
        }

        return response('',202);
    }

    public function destroy(CeProconminuta $minuta)
    {

        $minuta->delete();
        return response(null,202);

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


}


