<?php

namespace App\Http\Controllers\OficinaJuridica;

use App\Exports\OficinaJuridica\OjTutelasInformeExport;
use App\Exports\OjTutelasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\OficinaJuridica\TutelaRequest;
use App\Http\Resources\OficinaJuridica\TutelaResource;
use App\Http\Resources\OficinaJuridica\TutelasResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnArchivo;
use App\Models\OficinaJuridica\OjTutela;
use App\Traits\ArchivoTrait;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class TutelaController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return TutelasResource::collection(
                OjTutela::with('juzgado', 'afiliado', 'pretencion', 'archivo', 'tipIdentidad', 'departamento', 'afiliados_tutelas.afiliado')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return TutelaResource::collection(OjTutela::with('juzgado', 'afiliado', 'pretencion', 'archivo', 'tipIdentidad', 'departamento')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(TutelaRequest $request)
    {
        DB::beginTransaction();
        $afiliadosTutelas = $request->afiliados_tutelas;
        $afiliados = (array) json_decode($afiliadosTutelas, true);
        $achivo_tutela = null;
        if($request->hasFile('archivo')){

            $achivo_tutela = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/tutela',
                ['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf']);
        }
        $oj_tutela = new OjTutela();
        $oj_tutela->fill($request->except('gn_archivo_id', 'afiliados_tutelas'));
        $oj_tutela->gn_archivo_id = $achivo_tutela ? $achivo_tutela->id : null;
        $oj_tutela->save();
        $oj_tutela->afiliados_tutelas()->createMany($afiliados);
        $oj_tutela->load('juzgado', 'afiliado', 'pretencion', 'archivo', 'tipIdentidad','departamento','afiliados_tutelas.afiliado');
        DB::commit();
        return new TutelasResource($oj_tutela);
    }

    public function show(OjTutela $oj_tutela)
    {
        return new TutelaResource($oj_tutela->load('juzgado',
            'afiliado', 'pretencion',
            'archivo', 'tipIdentidad',
            'contestaciones.archivo', 'fallos.archivo',
            'impugnaciones.archivo', 'desacatos.archivo',
            'bitacoras.archivo', 'impugnaciones.tutfallo', 'departamento', 'afiliados_tutelas.afiliado', 'afiliados_tutelas.tipo_id'));
    }

    public function update(Request $request, $id){}

    public function destroy($id) {}

    public function actualizar (TutelaRequest $request, $id) {
        $afiliadosTutelas = $request->afiliados_tutelas;
        $afiliados = (array) json_decode($afiliadosTutelas, true);
        $tutela = OjTutela::find($id);
        $tutela->fill($request->except('archivo', 'afiliados_tutelas'));
        if ($request->hasFile('archivo')) {
            if($tutela->archivo != null){
                Storage::delete($tutela->archivo->ruta);
                $archivo = $tutela->archivo;
            }
            $achivo_tutela = ArchivoTrait::subirArchivo($request->file('archivo'), 'OficinaJuridica/tutela',
                ['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf']);
            $tutela->gn_archivo_id = $achivo_tutela->id;
            $tutela->save();
            $archivo ? $archivo->delete() : null;
        }
        $tutela->save();
        $tutela->afiliados_tutelas()->delete();
        $tutela->afiliados_tutelas()->createMany($afiliados);
        $tutela->load('juzgado', 'afiliado', 'pretencion', 'archivo', 'tipIdentidad', 'departamento', 'afiliados_tutelas.afiliado');
        return response()->json([
            'message' => 'Se ha actualizado correctamente la tutela.',
            'data' => new TutelasResource($tutela)
        ]);
    }

    public function downloadFile ($file) {
        $archivo_tutela = GnArchivo::find($file);
        return Storage::download($archivo_tutela->ruta, $archivo_tutela->nombre);
    }

    public function reporteTutela () {
        $date1 = Input::get('rangoInicial');
        $date2 = Input::get('rangoFinal');
        $mime = Input::get('mimeType');
        $item = Input::get('item');

        $mesUno = Carbon::createFromFormat('Y-m-d', $date1);
        //$mesUno->firstOfMonth()->toDateString();
        $mesUno->firstOfQuarter()->toDateString();
        $mesFinal = Carbon::createFromFormat('Y-m-d', $date2);
        $mesFinal->lastOfQuarter()->toDateString();
        //$mesFinal->lastOfMonth()->toDateString();
        $reportesTutela = OjTutela::whereBetween('fecha', [$mesUno, $mesFinal])->get();
        $reportesTutela->load(['juzgado',
            'afiliado.tipo_afiliado','afiliado.municipio', 'pretencion',
            'archivo', 'tipIdentidad',
            'contestaciones.archivo', 'fallos.archivo',
            'impugnaciones.archivo', 'desacatos.archivo',
            'bitacoras.archivo', 'impugnaciones.tutfallo', 'departamento', 'afiliados_tutelas.afiliado', 'afiliados_tutelas.tipo_id']);
        if ($mime === 'application/pdf') {
            return $this->getPdf($reportesTutela);
        } else {
            if ($item === "1"){
                return $this->getExcel($reportesTutela);
            } else {
                return $this->getIndicator($reportesTutela);
            }
        }
    }

    public function getIndicator ($data)
    {
        return (new OjTutelasInformeExport($data))->download('indicador.xlsx');
    }

     public function getPdf ($data) {
         if (Input::get('html')) { return view('dompdf.reporte_tutela'); }
         $pdf = PDF::loadView('dompdf.reporte_tutela', ['reportes' => $data]);
         $pdf->setPaper('letter', 'landscape');
         return $pdf->stream('Tutela', ['Attachment' => 0]);
     }

     public function getExcel ($data) {
        return (new OjTutelasExport($data))->download('reportes.xlsx');
     }

     public function firmarReporte () {
        return URL::temporarySignedRoute('reporteTutela', now()->addMinute(1), ['rangoInicial' => Input::get('rangoInicial'),'rangoFinal' => Input::get('rangoFinal'),'mimeType' => Input::get('mimeType'),'item' => Input::get('item')]);
     }

    public function findAfiliadoIdentificacion ($identificacion)
    {
        try {

            $afiliado = AsAfiliado::where('identificacion', $identificacion)->first();
            if ($afiliado) {
                return response()->json([
                    'exists' => true,
                    'message' => 'Se ha encontrado la identificación entre los registros de afiliados.',
                    'data' => new Resource($afiliado),
                ], 200);
            } else {
                return response()->json([
                    'exists' => false,
                    'message' => 'El número de identificación no se encuentra en los registros de afiliados.',
                ]);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'message' => 'Error al intentar buscar si existe el registro.',
            ], 500);
        }
    }
}
