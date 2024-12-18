<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Requests\RedServicios\PortabilidadRequest;
use App\Http\Resources\RedServicios\PortabilidadResource;
use App\Models\RedServicios\RsCancelportabilidade;
use App\Models\RedServicios\RsMaestroips;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\RedServicios\RsPortabilidade;
use App\Http\Controllers\Controller;
use App\Traits\ArchivoTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class PortabilidadController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return  PortabilidadResource::collection(
                RsPortabilidade::with('afiliado','municipio_receptor')->pimp()
                    ->orderBy('estado','asc')->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return PortabilidadResource::collection(
            RsPortabilidade::with('afiliado','municipio_receptor')->pimp()->orderBy('estado','asc')->orderBy('updated_at','desc')->get()
        );
    }


    public function store(PortabilidadRequest $request)
    {
        $portabilidad = RsPortabilidade::create($request->all());
        $this->subirAnexos($portabilidad,$request);
        $portabilidad->load('afiliado','entidad','archivo','usuario','usuario_tramita');
        return response()->json(
            [
                'portabilidad'  => new Resource($portabilidad),
                'portabilidad_o'=> new PortabilidadResource($portabilidad)
            ], 201
        );
    }


    public function show(RsPortabilidade $portabilidade)
    {
        $portabilidade->load('afiliado','entidad','archivo','usuario','usuario_tramita','municipio_receptor','serviciosPortabilidad.servicio_portabilidad','cancelPortabilidad','ipsGrupo.grupoIps.prestadores.entidad');
        return new Resource($portabilidade);
    }


    public function update(PortabilidadRequest $request, RsPortabilidade $portabilidade)
    {
        try {
            $portabilidade->estado = $request->estado;
            $portabilidade->fecha_tramite = Carbon::now()->toDateString();
            $portabilidade->observaciones = $request->observaciones;
            $portabilidade->motivo = $request->motivo;
            $portabilidade->email = $request->email;

            if ($request->estado === 'Aceptado') {
                //$portabilidade->ipsGrupo()->create([
                //    'id_grupoips' => $request->id_grupoips,
                //    'as_afiliado_id' => $request->as_afiliado_id,
                //    'gs_usuario_id' => Auth::user()->id
                //]);
                $this->assignationsServices($portabilidade, $request);
            }

            $this->subirAnexos($portabilidade,$request);
            $portabilidade->save();
            //$portabilidade->update($request->all());
            $portabilidade->load('afiliado','entidad','archivo','usuario','usuario_tramita');

            return response()->json(
                [
                    'portabilidad'  => new Resource($portabilidade),
                    'portabilidad_o'=> new PortabilidadResource($portabilidade)
                ], 202
            );
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ],500);
        }
    }

    public function subirAnexos(RsPortabilidade $portabilidad, Request $request)
    {
        if(!$request->hasFile('archivo')){return;}

        $mes = Carbon::parse($portabilidad->fecha_solicitud)->format('Ym');
        $path_storage = 'Aseguramiento/Portabilidades/'.$mes;

        $archivo = ArchivoTrait::subirArchivo($request->archivo, $path_storage);
        $portabilidad->gn_archivo_id = $archivo->id;

        $portabilidad->save();
    }

    public function getPdfSolicitud(){
        $id = Input::get('id');
        $portabilidad = RsPortabilidade::with('municipio_receptor', 'afiliado')->find($id);
        if (Input::get('html')) {
            return view('dompdf.solicitud_portabilidad');
        }
        setlocale(LC_ALL, "es_ES");
        //return view('dompdf.referencia', ['pqrs' => $pqrs]);
        $pdf = PDF::loadView('dompdf.solicitud_portabilidad', ['portabilidad' => $portabilidad, 'fecha' => strftime('%d de %B del %Y', strtotime($portabilidad->fecha_solicitud))]);
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('Solicitud Portabilidad No. '.$portabilidad->consecutivo,['Attachment' => 0]);
    }


    public function assignationsServices ($portabilidade, $request) {

        if (!$request->servicios_portabilidad) {
            throw new \Exception('No se estan enviando los servicios.');
        }

        $total = [];
        foreach ($request->servicios_portabilidad as $key => $servicie) {
            array_push($total, $servicie);
            $portabilidade->serviciosPortabilidad()->create(
                [
                    'as_afiliado_id' => $request->as_afiliado_id,
                    'rs_servportabilidad_id' => $servicie
                ]
            );
        }
    }


    public function cancelarPotabilidad (Request $request, $portabilidade) {
        try {
            $portabilidad = RsPortabilidade::select('id','estado')
                ->where('id',$portabilidade)->first();

            $cancelar = new RsCancelportabilidade();
            $cancelar->rs_portabilidade_id = $request->rs_portabilidade_id;
            $cancelar->descripcion = $request->descripcion;
            $cancelar->gs_usuario_id = Auth::user()->id;
            $cancelar->save();

            $portabilidad->estado = 'Cancelada';
            $portabilidad->save();

            return response()->json([
                'data' => new PortabilidadResource($portabilidad)
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'msg' => $e
            ], 500);
        }

    }

}
