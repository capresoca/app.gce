<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Http\Requests\Autorizaciones\AutorizacionRequest;
use App\Http\Resources\Autorizaciones\AutorizacionesResource;
use App\Models\Aseguramiento\AsRegimene;
use App\Models\Autorizaciones\AuAutaprobada;
use App\Models\Autorizaciones\AuAutorizacione;
use App\Models\Autorizaciones\AuAutsolicitude;
use App\Models\General\GnArchivo;
use App\Models\RedServicios\RsCuotacopago;
use App\User;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class AutorizacionController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return  AutorizacionesResource::collection(
                AuAutorizacione::pimp()
                    ->orderBy('updated_at')
                    ->paginate(Input::get('per_page'))
            );
        }

        return AutorizacionesResource::collection(
            AuAutorizacione::pimp()->get()
        );
    }


    public function autAprobadas(){


        $aprobadas = AuAutaprobada::join('au_autorizaciones', 'au_autaprobadas.au_autorizacion_id','au_autorizaciones.id')
            ->join('as_afiliados', 'au_autorizaciones.as_afiliado_id', 'as_afiliados.id')
            ->join('rs_entidades', 'au_autorizaciones.entidad_origen_id', 'rs_entidades.id')
            ->join('rs_servcontratados', 'au_autorizaciones.rs_servicio_id', 'rs_servcontratados.id')
            ->join('rs_servicios', 'rs_servcontratados.rs_servicio_id', 'rs_servicios.id')
            ->select(DB::raw('LPAD(au_autaprobadas.consecutivo,7,"0")'),
                DB::raw('1 as tipo'),
                'au_autaprobadas.id',
                DB::raw('as_afiliados.nombre_completo as nombre_afiliado'),
                DB::raw('rs_entidades.nombre as origen'),
                DB::raw('rs_servicios.nombre as servicio'),
                'au_autaprobadas.estado',
                DB::raw('as_afiliados.identificacion as identificacion'));
        $autorizaciones = AuAutorizacione::join('as_afiliados', 'au_autorizaciones.as_afiliado_id', 'as_afiliados.id')
            ->join('rs_entidades', 'au_autorizaciones.entidad_origen_id', 'rs_entidades.id')
            ->join('rs_servcontratados', 'au_autorizaciones.rs_servicio_id', 'rs_servcontratados.id')
            ->join('rs_servicios', 'rs_servcontratados.rs_servicio_id', 'rs_servicios.id')
            ->select(DB::raw(" '' as consecutivo"),
                DB::raw('2 as tipo'),
                'au_autorizaciones.id',
                DB::raw('as_afiliados.nombre_completo as nombre_afiliado'),
                DB::raw('rs_entidades.nombre as origen'),
                DB::raw('rs_servicios.nombre as servicio'),
                'au_autorizaciones.estado',
                DB::raw('as_afiliados.identificacion as identificacion'))
            ->where('au_autorizaciones.estado', 'Registrada')
            ->union($aprobadas);



        if(Input::get('per_page')){

            return  Resource::collection(
                $autorizaciones->pimp()
                    ->orderBy('consecutivo')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
           $autorizaciones->pimp()->get()
        );
    }

    public function store(AutorizacionRequest $request)
    {
        DB::beginTransaction();

        $autorizacion = new AuAutorizacione;

        $autorizacion->fill($request->except('detalles','afiliado','contrato','entidad_origen','historia_clinica','orden_medica'));
        $autorizacion->as_regimen_id = $request->afiliado['as_regimene_id'];
        $autorizacion->historia_clinica_id = $this->subirArchivoB64($request['historia_clinica']);
        $autorizacion->orden_medica_id = $this->subirArchivoB64($request['orden_medica']);
        $autorizacion->save();

        $autorizacion->detalles()->createMany($request->detalles);

        if($autorizacion->estado === 'Confirmada'){
            $this->radicar($autorizacion, $request->afiliado['rango_contributivo']);
        }

        DB::commit();

        $autorizacion->load('aprobaciones.detalles',
            'solicitudes.detalles');

        return response([
            'autorizacion' => new Resource($autorizacion),
            'autorizacion_o' => new AutorizacionesResource($autorizacion),
            'solicitud_o'       => new Resource($autorizacion->solicitudes)
        ],201);
    }

    public function update(AutorizacionRequest $request, AuAutorizacione $autorizacione){

        DB::beginTransaction();
        $estado_inicial = $autorizacione->estado;
        $request['as_regimen_id'] = $request->afiliado['as_regimene_id'];
        $autorizacione->update($request->except('detalles','afiliado','contrato','entidad_origen','historia_clinica','orden_medica'));

        if(!isset($request['historia_clinica']['id']))
            $autorizacione->historia_clinica_id = $this->subirArchivoB64($request['historia_clinica']) ;

        if(!isset($request['orden_medica']['id']))
            $autorizacione->orden_medica_id = $this->subirArchivoB64($request['orden_medica']);

        $autorizacione->save();

        $autorizacione->detalles()->delete();

        $autorizacione->detalles()->createMany($request->detalles);


        if($estado_inicial != 'Confirmada' && $autorizacione->estado === 'Confirmada'){
            $this->radicar($autorizacione, $request->afiliado['rango_contributivo']);
        }

        $autorizacione->load('detalles');
        DB::commit();

        return response([
            'autorizacion'   => new Resource($autorizacione),
            'autorizacion_o' => new AutorizacionesResource($autorizacione),
            'solicitud_o'       => new Resource($autorizacione->solicitudes)
        ],202);
    }

    public function show(AuAutorizacione $autorizacione)
    {
        $autorizacione->load([
            'detalles',
            'afiliado.regimen',
            'entidad_origen.municipio',
            'servicio.servicio',
            'contrato.contrato.entidad',
            'cie10_principal',
            'cie10_rel1',
            'cie10_rel2',
            'historia_clinica',
            'orden_medica',
            'aprobaciones.detalles',
            'solicitudes.detalles',
            'contrato.servicios_contratados.servicio'
        ]);
        return new Resource($autorizacione);
    }

    private function subirArchivoB64($archivo)
    {
        if(!$archivo){
            return null;
        }

        if(!isset($archivo['string'])) return null;
        $array_base64 = explode(';',$archivo['string']);
        $file = explode(',',$array_base64[1]);
        $file = base64_decode($file[1]);

        $anioMes = today()->format('Ym');
        $ruta = 'Autorizaciones/'.$anioMes.'/'.$archivo['name'];
        Storage::put($ruta,$file);

        $archivo = GnArchivo::create([
            'nombre'    => $archivo['name'],
            'size'      => $archivo['size'],
            'extension' => $archivo['type'],
            'ruta'      => $ruta
        ]);

        return $archivo->id;
    }

    private function radicar(AuAutorizacione $autorizacione, $cuotacopago)
    {
        $this->crearAprobacion($autorizacione, $cuotacopago);
        $this->crearSolicitud($autorizacione);

    }

    private function crearSolicitud(AuAutorizacione $autorizacione)
    {
        $itemsRequierenAutorizacion =  $autorizacione->detalles()->byNivelAutorizacion('Auditor Medico')->get();

        if($itemsRequierenAutorizacion->count()){
            $solicitud = AuAutsolicitude::create([
                'au_autorizacion_id'    => $autorizacione->id,
                'estado'                => 'Confirmada'
            ]);

            $autorizacione->detalles()->byNivelAutorizacion('Auditor Medico')->update([
                'au_autsolicitud_id'    => $solicitud->id,
            ]);
        }

    }

    private function crearAprobacion(AuAutorizacione $autorizacione, $rangoContributivo)
    {
        $itemsNoRequierenAutorizacion =  $autorizacione->detalles()->byNivelAutorizacion('Asesor')->get();

        if($itemsNoRequierenAutorizacion->count()){
            $aprobacion = AuAutaprobada::create([
                'au_autorizacion_id'    => $autorizacione->id,
                'rs_contrato_id'        => $autorizacione->rs_contrato_id,
                'destino'               => $autorizacione->tipo_destino,
                'valor_total'           => $itemsNoRequierenAutorizacion->sum('valor'),
                'valor_usuario'         => $itemsNoRequierenAutorizacion->sum('valor_usuario'),
                'estado'                => 'Activa'
            ]);
            $cuota = false;
            foreach ($itemsNoRequierenAutorizacion as $item) {
                $item->au_autaprobada_id = $aprobacion->id;
                $item->cantidad_aprobada = $item->cantidad_solicitada;
                $item->save();
                if(!$cuota && $item->tipo_valor == "Cuota Moderadora"){
                    $cuota = true;
                }
            }
            if ($cuota){
                $aprobacion->valor_usuario = $rangoContributivo != null ? $rangoContributivo['cuota_moderadora'] : $aprobacion->valor_usuario;
                $aprobacion->save();
            }
        }
    }

    public function getPdf () {
        try {
            $id = Input::get('id');
            $autaprobada = AuAutaprobada::find($id);
            $user = User::find(Input::get('user'));

            $autaprobada->load([
                'detalles',
                'autorizacion.afiliado.regimen',
                'autorizacion.entidad_origen.municipio',
                'autorizacion.servicio.servicio',
                'autorizacion.cie10_principal',
                'autorizacion.cie10_rel1',
                'autorizacion.cie10_rel2',
                'autorizacion.historia_clinica',
                'autorizacion.orden_medica',
                'usuario',
                'anula'
            ]);

            if (Input::get('html')) {
                return view('dompdf.autorizacion', ['autaprobada' => $autaprobada, 'imprime' => $user]);
            }
            // return view('dompdf.autorizacion', ['autaprobada' => $autaprobada]);
            $pdf = PDF::loadView('dompdf.autorizacion', ['autaprobada' => $autaprobada, 'imprime' => $user]);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('Autorización de Servicios Médicos Asistenciales', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteAutorizacion(Request $request, $id){

        if($request->motivo_anula != ""){
            if($request->tipo == '1'){
                $autAprobada = AuAutaprobada::find($id);
                $autAprobada->estado = 'Anulada';
                $autAprobada->motivo_anula = $request->motivo_anula;
                $autAprobada->user_anula_id = Auth::user()->id;
                $autAprobada->fecha_anula = Carbon::now()->toDateTimeString();
                $autAprobada->save();

                return response("ok", 200);
            }else if($request->tipo == '2'){
                $autorizacion = AuAutorizacione::find($id);
                $autorizacion->estado = 'Anulada';
                $autorizacion->motivo_anula = $request->motivo_anula;
                $autorizacion->fecha_anula = Carbon::now()->toDateTimeString();
                $autorizacion->user_anula_id = Auth::user()->id;
                $autorizacion->save();

                return response('ok', 200);
            }


        }
        return response("Motivo anulación requerido", 422);

    }

}
