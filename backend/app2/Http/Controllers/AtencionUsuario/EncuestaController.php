<?php

namespace App\Http\Controllers\AtencionUsuario;

use App\Http\Requests\AtencionUsuario\EncuestaRequest;
use App\Http\Resources\AtencionUsuario\EncuestaResource;
use App\Models\AtencionUsuario\AuEncpregunta;
use App\Models\AtencionUsuario\AuEncuesta;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class EncuestaController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return EncuestaResource::collection(
                AuEncuesta::with( 'afiliado', 'municipio')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return EncuestaResource::collection(AuEncuesta::with('afiliado', 'municipio')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(EncuestaRequest $request)
    {
//        dd($request->all());
        try {
            $encuestaArray = $request->toArray();
            $au_encuesta = AuEncuesta::create($encuestaArray);
            $au_encuesta->respuestas()->createMany($encuestaArray['respuestas']);
            $au_encuesta->load('afiliado','municipio','respuestas');
            return new EncuestaResource($au_encuesta);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function show(AuEncuesta $au_encuesta)
    {
        return new EncuestaResource($au_encuesta->load('afiliado','municipio','respuestas'));
    }

    public function update(EncuestaRequest $request, AuEncuesta $au_encuesta)
    {
        $encuestaArray = $request->toArray();
//        $au_encuesta = AuEncuesta::findOrFail($encuestaArray['id']);
        $au_encuesta->update($encuestaArray);
        $au_encuesta->respuestas()->delete();
        $au_encuesta->respuestas()->createMany($encuestaArray['respuestas']);
        $au_encuesta->load('afiliado','municipio');
        return new EncuestaResource($au_encuesta);
    }

    public function destroy($id) {}

    public function getPdf () {
        try {
            $id = Input::get('id');
            $au_encuesta = AuEncuesta::find($id);
            $au_encuesta->load('afiliado','municipio.departamento','respuestas.pregunta');
            if (Input::get('html')) {
                return view('dompdf.encuesta');
            }
            $pdf = PDF::loadView('dompdf.encuesta', ['au_encuesta' => $au_encuesta]);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('Encuesta del Estado de Salud', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function complementos()
    {
        $preguntas = collect(AuEncpregunta::all());
        return response()->json(
            [
                'preguntas' => $preguntas
            ]
        );
    }
}
