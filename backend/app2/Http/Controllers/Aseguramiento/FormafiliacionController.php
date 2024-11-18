<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\GeneradorAfitramites;
use App\Http\Requests\Aseguramiento\FormafiliacionRequest;
use App\Http\Resources\Aseguramiento\FormafiliacionesResource;
use App\Http\Resources\Aseguramiento\FormafiliacionResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsFormafiliacione;
use App\Models\Aseguramiento\AsNucfamiliare;
use App\Models\Niif\GnTercero;
use App\Models\RedServicios\RsAfiliadoServicio;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;

class FormafiliacionController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return FormafiliacionesResource::collection(
                AsFormafiliacione::with(
                    'afiliado',
                    'beneficiarios')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return FormafiliacionResource::collection(AsFormafiliacione::with(
            'afiliado.tercero',
            'beneficiarios'
        )->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(FormafiliacionRequest $request){
//        try{

            DB::beginTransaction();
            $formafiliacione = $this->crearFormafiliacion($request);
            $estadoRegistrado = 2;
            $this->actualizarEstadoAfiliados($formafiliacione, $estadoRegistrado);

            $formafiliacione->load(
                'claseCotizante',
                'afiliado.municipio',
                'ips.tercero',
                'nucleo_familiar.parentesco',
                'nucleo_familiar.ips.tercero',
                'nucleo_familiar.beneficiario',
                'anexos.anexo'
            );

            if($formafiliacione->estado === 'Radicado'){
                $this->radicar($formafiliacione);
            }

            DB::commit();

            return response()->json([
                'message' => 'El tramite de afiliación fue guardado con exito',
                'formafiliacion' => new FormafiliacionResource($formafiliacione),
                'formafiliacion_o' => new FormafiliacionesResource($formafiliacione),
            ],201);

//        }  catch (\Exception $e)
//        {
//            DB::rollBack();
//            return response()->json([
//                'errors' => $e,
//                'message' => $e->getMessage()
//            ], 500);
//        }

    }

    public function update(FormafiliacionRequest $request, AsFormafiliacione $formafiliacione)
    {
        try{
            $estadoProcesoRegistro = 1;
            $estadoRegistrado = 2;
            $estadoAfiliados = $estadoRegistrado;

            $estado_actual = $formafiliacione->estado;
            $formafiliacione->update($request->all());

            if($request->estado === 'Radicado' && $estado_actual === 'Registrado')
            {
                $this->radicar($formafiliacione);
            }


            if($request->estado === 'Anulado' && ($estado_actual === 'Registrado' || $estado_actual === 'Radicado' || $estado_actual === 'Anulado'))
            {
                $estadoAfiliados = $estadoProcesoRegistro;

                if(!$this->anular($formafiliacione))
                {
                    return response()->json([
                        'message' => 'No se puede anular el fomulario'
                    ], 405);
                }
            }
            $this->actualizarEstadoAfiliados($formafiliacione, $estadoAfiliados);

            $formafiliacione->load(
                'claseCotizante',
                'afiliado.municipio',
                'ips.tercero',
                'nucleo_familiar.parentesco',
                'nucleo_familiar.ips.tercero',
                'nucleo_familiar.beneficiario.tercero',
                'anexos.anexo'
            );
            return response()->json([
                'message' => 'El tramite de afiliación fue guardado con exito',
                'formafiliacion' => new FormafiliacionResource($formafiliacione),
                'formafiliacion_o' => new FormafiliacionesResource($formafiliacione),
            ],202);
        } catch (\Exception $e){
            return $e;
        }
    }

    public function show(AsFormafiliacione $formafiliacione)
    {
        $formafiliacione->load(
        'claseCotizante',
        'afiliado.municipio',
        'ips.tercero',
        'municipio',
        'beneficiarios',
        'nucleo_familiar.parentesco',
        'nucleo_familiar.ips.tercero',
        'nucleo_familiar.beneficiario.tercero',
        'anexos.anexo');

        return new FormafiliacionResource($formafiliacione);
    }


    public function getPdf(AsFormafiliacione $formafiliacion)
    {
        try{

            //            return 'algo';
            $formafiliacion->load(['afitramite.tramite']);

            if(Input::get('html')){
                return view('dompdf.afitramites');
            }

            $pdf = PDF::loadView('dompdf.afitramite', ['afitramite' => $formafiliacion]);
            $pdf->setPaper('letter', 'portrait');


            return $pdf->stream('Formulario de afiliacion '.$formafiliacion->afiliado->identificacion, ['Attachment' => 0]);
        }catch (\Exception $e){
            return $e;
        }
    }

    /**
     * @return mixed
     */
    public function rutaPdf()
    {
        return URL::temporarySignedRoute('pdf_formafiliacion', now()->addMinutes(60), [Input::get('id')]);

    }

    private function radicar(AsFormafiliacione $formafiliacione)
    {
        $fecha_afiliacion = $formafiliacione->fecha_radicacion;
        $formafiliacione->no_radicado = AsFormafiliacione::max('no_radicado') + 1;
        if(Auth::user()) {
            $formafiliacione->usuradica_id = Auth::user()->id;
            $formafiliacione->save();
        }
        $formafiliacione->save();
        if(!$formafiliacione->recien_nacido){
            $formafiliacione->afiliado->fecha_afiliacion = $fecha_afiliacion;
            $formafiliacione->afiliado->save();
        }

        foreach ($formafiliacione->beneficiarios as $beneficiario) {
            $beneficiario->fecha_afiliacion = $fecha_afiliacion;
            $beneficiario->cabfamilia_id = $formafiliacione->afiliado->id;
            $beneficiario->as_parentesco_id = $beneficiario->pivot->as_parentesco_id;
            $beneficiario->save();
        }
        new GeneradorAfitramites($formafiliacione);
    }

    private function anular(AsFormafiliacione $formafiliacione)
    {

        if($formafiliacione->afitramites){
            foreach ($formafiliacione->afitramites as $afitramite){
                if($afitramite->tramite->as_bduaarchivo_id){
                    return false;
                }
            }
            $formafiliacione->afitramites()->delete();
            $formafiliacione->estado = 'Anulado';
            $formafiliacione->save();
        }

        return true;
    }

    private function actualizarEstadoAfiliados(AsFormafiliacione $formafiliacione, $estado)
    {
        if(!$formafiliacione->recien_nacido){
            $formafiliacione->afiliado->estado = $estado;
            $formafiliacione->afiliado->as_regimene_id = $formafiliacione->as_regimene_id;
            $formafiliacione->afiliado->save();
        }

        foreach ($formafiliacione->beneficiarios as $beneficiario) {
            if(!$formafiliacione->recien_nacido){
                $beneficiario->estado = $estado;
                $beneficiario->as_regimene_id = $formafiliacione->as_regimene_id;
                $beneficiario->save();
            }
        }
    }

    private function crearFormafiliacion($request)
    {
        if(!$request->recien_nacido){
            return AsFormafiliacione::create($request->all());
        }

        $responsable = AsAfiliado::find($request->responsable_id);

        $formafiliacion = AsFormafiliacione::create([
            'as_regimene_id' => $responsable->as_regimene_id,
            'as_tipafiliacione_id' => 1,
            'as_tipafiliado_id' => $responsable->as_tipafiliado_id,
            'as_clasecotizante_id' => $responsable->as_clasecotizante_id,
            'as_afiliado_id' => $responsable->id,
            'rs_ips_id' => $responsable->rs_ips_id,
            'estado' => $request->estado,
            'recien_nacido' => $request->recien_nacido,
            'fecha_radicacion' => today()->toDateString()
        ]);

        foreach ($request->recien_nacidos as $recienNacido) {
            $afiliadoRecienNacido = $this->crearAfiliadoRecienNacido($recienNacido,$responsable);
            AsNucfamiliare::create(
                [
                    'as_beneficiario_id' => $afiliadoRecienNacido->id,
                    'as_parentesco_id' => 2,
                    'rs_entidade_id' => $responsable->rs_entidade_id ? $responsable->rs_entidade_id : 1,
                    'as_formafiliacion_id' => $formafiliacion->id
                ]
            );
        }

        return $formafiliacion;

    }

    private function crearAfiliadoRecienNacido($recienNacido, $responsable)
    {

        $estadoActivo = 3;
        $regimenSubsidiado = 2;
        $tipoAfiliadoBeneficiario = 3;

        $cabeza = $responsable->id;

        if($responsable->cabeza){
            $cabeza = $responsable->cabeza->id;
        }
        $recienNacido = AsAfiliado::create(
            [
                'as_regimene_id' => $responsable->as_regimene_id,
                'nombre1' => $recienNacido['nombre1'],
                'nombre2' => $recienNacido['nombre2'],
                'apellido1' => $recienNacido['apellido1'],
                'apellido2' => $recienNacido['apellido2'],
                'gn_tipdocidentidad_id' => $recienNacido['gn_tipdocidentidad_id'],
                'direccion' => $responsable->direccion,
                'telefono_fijo' => $responsable->telefono_fijo,
                'celular' => $responsable->celular,
                'correo_electronico' => $responsable->correo_electronico,
                'gn_municipio_id' => $responsable->gn_municipio_id,
                'as_etnia_id' => $responsable->as_etnia_id,
                'as_parentesco_id' => 2,
                'cabfamilia_id' => $cabeza,
                'as_condicion_discapacidades_id' => !isset($recienNacido['as_condicion_discapacidades_id'])?NULL:$recienNacido['as_condicion_discapacidades_id'],
                'as_pobespeciale_id' => $responsable->as_pobespeciale_id,
                'rs_entidade_id' => $responsable->rs_entidad_id,
                'estado' => $estadoActivo,
                'gn_zona_id' => $responsable->gn_zona_id,
                'gn_vereda_id' => $responsable->gn_vereda_id,
                'fecha_nacimiento' => $recienNacido['fecha_nacimiento'],
                'gn_sexo_id' => $recienNacido['gn_sexo_id'],
                'as_tipafiliado_id' => $tipoAfiliadoBeneficiario,
                'ficha_sisben' => $responsable->ficha_sisben,
                'puntaje_sisben' => $responsable->puntaje_sisben,
                'nivel_sisben' => $responsable->nivel_siben,
                'zona_dnp' => $responsable->zona_dnp,
                'gn_barrio_id' => $responsable->gn_barrio_id,
                'fecha_afiliacion' => $recienNacido['fecha_nacimiento'],
                'nombre_completo' => '',
                'es_nacimiento' => 1,
                'tipo_identificacion' => '',
                'identificacion' => $recienNacido['identificacion'],
                'fecha_expedicion' => $recienNacido['fecha_expedicion'],
                'gn_paise_id' => $responsable['gn_paise_id'],
            ]
        );

        $this->heredarServicios($responsable->id, $recienNacido);

        return $recienNacido;
    }

    public function heredarServicios ($id, $recienNacido) {

        $responsable = AsAfiliado::with('regimen','cabeza')->find($id);
        $afiliado = $responsable->cabeza ?? $responsable;
        $totServicios = count($afiliado->servicios_habilitados);

        if ($totServicios > 0) {
            foreach ($afiliado->servicios_habilitados as $key => $habilitado) {
                $afiservicio = new RsAfiliadoServicio();
                $afiservicio->as_afiliado_id = $recienNacido->id;
                $afiservicio->rs_servhabilitado_id = $habilitado['id'];
                $afiservicio->save();
            }
        }
    }

}
