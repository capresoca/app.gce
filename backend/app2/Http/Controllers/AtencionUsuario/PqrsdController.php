<?php

namespace App\Http\Controllers\AtencionUsuario;

use App\Events\UserNotificationsEvent;
use App\Exports\AtencionUsuario\PqrsdExport;
use App\Http\Requests\AtencionUsuario\PqrsdRequest;
use App\Http\Resources\AtencionUsuario\pqrsdResource;
use App\Models\AtencionUsuario\AuMacromotivo;
use App\Models\AtencionUsuario\AuModServiciosPqr;
use App\Models\AtencionUsuario\AuMotQuejapqr;
use App\Models\AtencionUsuario\AuPqrsd;
use App\Models\AtencionUsuario\AuTipopqrsd;
use App\Traits\ArchivoTrait;
use App\Traits\EnumsTrait;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PqrsdController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return  pqrsdResource::collection(
                AuPqrsd::with('afiliado','tipo','funcionario','municipio')->pimp()
                    ->orderBy('estado')
                    ->orderBy('fecha_limite')
                    ->orderBy('es_prioritaria')
                    ->paginate(Input::get('per_page'))
            );
        }
        return new pqrsdResource(
            AuPqrsd::with('afiliado','tipo','funcionario','municipio')
                ->pimp()->orderBy('updated_at','desc')->get()
        );
    }

    public function store(PqrsdRequest $pqrsdRequest)
    {
        $pqr = AuPqrsd::create($pqrsdRequest->except('files'));

        $this->subirAnexos($pqr, $pqrsdRequest->file('files'));

        try{
            broadcast(
                new UserNotificationsEvent(
                    [
                        "type" => "pqrs",
                        "message"=> [
                            'title' => 'Se asignó una nueva '.$pqr->tipo->descripcion,
                            'text'=> $pqr->relato
                        ]
                    ],
                    $pqr->funcionario
                )
            );
        }catch (\Exception $e)
        {
            Log::error($e);
        }

        $pqr->estado;
        $pqr->load('afiliado','tipo','funcionario','municipio','anexos');

        return new pqrsdResource($pqr);
    }

    public function preview(PqrsdRequest $pqrsdRequest)
    {
        $pqr = AuPqrsd::create($pqrsdRequest->except('files'));
    }

    public function update(PqrsdRequest $pqrsdRequest,AuPqrsd $pqrsd)
    {
        $pqrsd->update($pqrsdRequest->except('files'));
        $this->subirAnexos($pqrsd,$pqrsdRequest->file('files'));

        $pqrsd->load('afiliado.municipio','funcionario', 'tipo','municipio','anexos','modservicio');

        return new pqrsdResource($pqrsd);

    }

    public function show(AuPqrsd $pqrsd){
        if(Auth::user() && Auth::user()->id === $pqrsd->funcionario->id && $pqrsd->estado === 'Registrado'){
            $pqrsd->estado = 'Leído';
            $pqrsd->save();
        }
        $pqrsd->load('afiliado.municipio','funcionario', 'tipo','municipio','anexos','respuesta.anexos','entidad','modservicio','all_respuestas.anexos');

        return new pqrsdResource($pqrsd);
    }

    public function complementos()
    {
        $funcionarios = User::where('tipo','Funcionario')->get();

        return response()->json(
            [
                'tipos' => AuTipopqrsd::all(),
                'medios' => EnumsTrait::getEnumValues('au_pqrsds', 'medio_recepcion'),
                'fuentes' => EnumsTrait::getEnumValues('au_pqrsds', 'fuente'),
                'funcionarios' => $funcionarios,
                'macromotivos' => AuMacromotivo::with(['children.children'])->get(),
                'modalidaservicios' => AuModServiciosPqr::all(),
                'motivosquejapqrs' => AuMotQuejapqr::all(),
            ]
        );
    }

    private function subirAnexos(AuPqrsd $pqr, $files)
    {
        if($files && count($files)){
            foreach ($files as $file) {
                $anexo = ArchivoTrait::subirArchivo($file, 'AtencionUsuario/PQRSDS');
                $pqr->anexos()->attach($anexo->id);
            }
        }
    }

    public function getReport(Excel $excel)
    {

        $pqrs = AuPqrsd::betweenDates()
                        ->with(['afiliado','tipo','municipio','respuesta'])->get();

        return $excel::download(new PqrsdExport($pqrs,$this->estadisticas()),'pqrs.xls');

    }

    private function estadisticas(){
        $estadisticas = array();
        $estadisticas['macromotivo_by_entidad'] = array();
        $estadisticas['by_macromotivo'] = AuPqrsd::joining()
            ->byMacromotivo()
            ->betweenDates()
            ->enContra()
            ->get()
            ->toArray();

        $macromotivos = AuMacromotivo::all();

        foreach ($macromotivos as $macromotivo){
            $pqrsEntidad = AuPqrsd::joining()
                ->enContra()
                ->macromotivoByEntidad($macromotivo->id)
                ->betweenDates()->get()->toArray();

            if(!$pqrsEntidad) continue;

            array_push(
            $estadisticas['macromotivo_by_entidad'],
                [
                    'descripcion'=>$macromotivo->descripcion,
                    'codigo'=>$macromotivo->codigo,
                    'entidades' => $pqrsEntidad

                ]
            );
        }


        return $estadisticas;
    }

    public function getPdfRespuesta(){
        try {
            $id = Input::get('id');
            $pqrs = AuPqrsd::whereId($id)->with([
                'respuesta',
                'afiliado',
                'municipio'

            ])->first();

            if (Input::get('html')) {
                return view('dompdf.pqrs_respuesta');

            }
            setlocale(LC_ALL, "es_ES");
            //return view('dompdf.referencia', ['pqrs' => $pqrs]);

            $pdf = PDF::loadView('dompdf.pqrs_respuesta', [
                'pqrs' => $pqrs,
                'fecha' => strftime('%d de %B del %Y', strtotime($pqrs->created_at)),
                'fecha_respuesta' => $pqrs->respuesta ? strftime('%d de %B del %Y', strtotime($pqrs->respuesta['fecha_respuesta'])) : 'Sin Respuesta']);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('Radicado Cuentas Medicas No. '.$pqrs->consecutivo,['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getPdfDescargos(){
        $id = Input::get('id');
        $pqrs = AuPqrsd::find($id)->load([
            'respuesta',
            'afiliado',
            'municipio',
            'entidad'

        ]);
        if (Input::get('html')) {
            return view('dompdf.pqrsd_descargos');
        }
        setlocale(LC_ALL, "es_ES");
        //return view('dompdf.referencia', ['pqrs' => $pqrs]);
        $fecha_res = $pqrs->fecha_respuesta ? $pqrs->fecha_respuesta : today()->toDateString();
        $pdf = PDF::loadView('dompdf.pqrsd_descargos', ['pqrs' => $pqrs, 'fecha' => strftime('%d de %B del %Y', strtotime($fecha_res))]);
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('Solicitud Descargos PQRSD No. '.$pqrs->consecutivo,['Attachment' => 0]);
    }
}
