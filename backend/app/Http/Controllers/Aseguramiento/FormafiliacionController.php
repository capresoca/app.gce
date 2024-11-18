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
use App\Models\Aseguramiento\BDUA\AfAfiliadoMs;
use Illuminate\Support\Facades\Log;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\TbVigenciaTraslado;
use App\Models\Enums\ESiNo;
use App\Models\Enums\ETipoNovedad;
use App\Models\General\GnTipdocidentidade;
use App\Http\Resources\Aseguramiento\AfiliadoFormResource;

class FormafiliacionController extends Controller
{
   /* public function index()
    {
        /* = AsFormafiliacione::with(
            'afiliado',
            'beneficiarios')
            ->pimp()
            ->orderBy('updated_at','desc')
            ->paginate(Input::get('per_page'));
            foreach ($var as $v) {
                var_dump($v);
            }
        exit;
        if(Input::get('per_page')){
            return FormafiliacionesResource::collection(
                AsFormafiliacione::with(
                    'afiliado.tercero',
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
    }*/
    
    public function index()
    {
        /* = AsFormafiliacione::with(
         'afiliado',
         'beneficiarios')
         ->pimp()
         ->orderBy('updated_at','desc')
         ->paginate(Input::get('per_page'));
         foreach ($var as $v) {
         var_dump($v);
         }
         exit;*/
        if(Input::get('per_page')){
            $consulta = FormafiliacionesResource::collection(
                AsFormafiliacione::with(
                    'afiliado.tercero',
                    'beneficiarios')
                ->pimp()
                ->orderBy('updated_at','desc')
                ->paginate(Input::get('per_page'))
                );
            if (count($consulta) !== 0) {
                return $consulta;
            } else {
                $afiliado = AsAfiliado::where('identificacion', Input::get('identificacion'))->pimp()->paginate(Input::get('per_page'));
                return AfiliadoFormResource::collection($afiliado);
            }
        }
        
        $consulta = FormafiliacionResource::collection(AsFormafiliacione::with(
            'afiliado.tercero',
            'beneficiarios'
            )->pimp()->orderBy('updated_at','desc')->get());
        if (count($consulta) !== 0) {
            return $consulta;
        } else {
            $afiliado = AsAfiliado::where('identificacion', Input::get('identificacion'))->pimp()->paginate(Input::get('per_page'));
            return AfiliadoFormResource::collection($afiliado);
        }
    }

    public function store(FormafiliacionRequest $request){
//        try{

            DB::beginTransaction();
            
            if(!$request->recien_nacido){
                $temporal           = $request;
                $requestCopy        = $request->toArray();
                $requestCopy['as_pagadore_id']    = empty($request->afiliado_pagador['as_pagadore_id']) ? NULL : $request->afiliado_pagador['as_pagadore_id'];
                $formafiliacione    = $this->crearFormafiliacion($requestCopy);
                $request            = $temporal;
            } else {
                $formafiliacione = $this->crearFormafiliacion($request);
            }
            
            if(!empty($request->afiliado_pagador['as_arl_id']) && !empty($request->as_regimene_id) && $request->as_regimene_id == 1) {
                //Log::info('ARL: ',array($request->afiliado_pagador['as_arl_id']));
                $afiliado                       = AsAfiliado::find($formafiliacione->as_afiliado_id);
                $afiliado->estado_traslado      = 'MC';
                $afiliado->as_arl_id            = $request->afiliado_pagador['as_arl_id'];
                
                //Log::info('ARL: ',array($request->afiliado_pagador));
                //Log::info('IBC: ',array($request->afiliado_pagador->ibc));
                $afiliado->ibc                  = $request->afiliado_pagador['ibc'];
                $afiliado->nf_ciiu_id           = $request->nf_ciiu_id;
                
                $afiliado->save();
                
                $pagador        = AsAfiliadoPagador::where('as_formafiliacione_id',$formafiliacione->id)->first();
                
                if(empty($pagador)) {
                    AsAfiliadoPagador::create(
                        [
                            'as_afiliado_id'            => $formafiliacione->as_afiliado_id,
                            'as_pagador_id'             => $request->afiliado_pagador['as_pagadore_id'],
                            'fecha_vinculacion'         => $request->afiliado_pagador['fecha_vinculacion'],
                            'ibc'                       => $request->afiliado_pagador['ibc'],
                            'estado'                    => 'Activo',
                            'as_formafiliacione_id'     => $formafiliacione->id,
                            'tipo_cotizante'            => $request->as_clasecotizante_id,
                            'as_clasecotizante_id'      => $request->as_clasecotizante_id,
                            'as_arl_id'                 => $request->afiliado_pagador['as_arl_id'],
                            'nf_ciiu_id'                => $request->nf_ciiu_id,
                        ]
                        );
                    //$pagador        = AsAfiliadoPagador::where('as_afiliado_id',$formafiliacione->as_afiliado_id)->first();
                } else {
                    $pagador->as_afiliado_id            = $formafiliacione->as_afiliado_id;
                    $pagador->as_pagador_id             = $request->afiliado_pagador['as_pagadore_id'];
                    $pagador->fecha_vinculacion         = $request->afiliado_pagador['fecha_vinculacion'];
                    $pagador->ibc                       = $request->afiliado_pagador['ibc'];
                    //$pagador->estado                    = $request->nf_ciiu_id;
                    $pagador->as_formafiliacione_id     = $formafiliacione->id;
                    $pagador->tipo_cotizante            = $request->as_clasecotizante_id;
                    $pagador->as_clasecotizante_id      = $request->as_clasecotizante_id;
                    $pagador->as_arl_id                 = $request->afiliado_pagador['as_arl_id'];
                    $pagador->nf_ciiu_id                = $request->nf_ciiu_id;
                    
                    $pagador->save();
                }
            }
            
            
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
    
    public function formDate(string $date)
    {
        if (!$date) return null;
        
        $dateSplit = explode('/', $date);
        return $dateSplit[2] . '-' . $dateSplit[1] . '-' . $dateSplit[0];
    }
    
    public function anularAfiliacion(Request $request) {
        try{
            Log::info('Revisar id anular: '.$request->id);
            $formafiliacione        = AsFormafiliacione::find($request->id);
            $estado_actual          = $formafiliacione->estado;
            
            if($request->estado === 'Anulado' && ($estado_actual === 'Registrado' || $estado_actual === 'Radicado' || $estado_actual === 'Anulado'))
            {
                
                if(!$this->anular($formafiliacione))
                {
                    Log::info('no anula: '.$request->id);
                    return response()->json([
                        'message' => 'No se puede anular el fomulario'
                    ], 405);
                } else {
                    Log::info('anula bien: '.$request->id);
                    return response()->json([
                        'message' => 'El tramite de afiliación fue guardado con exito',
                    ],202);
                }
            }
        } catch (\Exception $e){
            Log::info('Tramite: '.$e->getMessage());
            return $e;
        }
    }

    public function update(FormafiliacionRequest $request, AsFormafiliacione $formafiliacione)
    {
        try{
            $estadoProcesoRegistro = 1;
            $estadoRegistrado = 2;
            $estadoAfiliados = $estadoRegistrado;

            $estado_actual = $formafiliacione->estado;
            
            $requestCopy = $request->toArray();
            
            $requestCopy['fecha_radicacion']    = $this->formDate($requestCopy['fecha_radicacion']);
            
            $formafiliacione->update($requestCopy);

            if($request->estado === 'Radicado' && $estado_actual === 'Registrado')
            {
                $this->radicar($formafiliacione);
                /*foreach ($formafiliacione->beneficiarios as $beneficiario) {
                    Log::info('Benerifiario Objeto Analizar: ', array($beneficiario));
                    $formularioA    = AsFormafiliacione::where('as_afiliado_id',$beneficiario->id)->first();
                    $formularioA->fecha_radicacion  = $requestCopy['fecha_radicacion'];
                    $formularioA->save();
                    $this->radicar($formularioA);
                    $this->actualizarEstadoAfiliados($formularioA, $estadoAfiliados);
                }*/
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
            
            if($request->estado === 'Radicado' && $estado_actual === 'Registrado')
            {
                foreach ($formafiliacione->beneficiarios as $beneficiario) {
                    Log::info('Benerifiario Objeto Analizar: ', array($beneficiario));
                    $formularioA    = AsFormafiliacione::where('as_afiliado_id',$beneficiario->id)->first();
                    $formularioA->estado    = $request->estado;
                    $formularioA->fecha_radicacion  = $requestCopy['fecha_radicacion'];
                    $formularioA->save();
                    $this->radicar($formularioA);
                    $this->actualizarEstadoAfiliados($formularioA, $estadoAfiliados);
                }
            }
            
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
            //var_dump($formafiliacion->afiliado->fecha_nacimiento);
            //var_dump($formafiliacion->afiliado); exit;
            //            return 'algo';
            $formafiliacion->load(['afitramite.tramite','afiliado','nucleo_familiar.beneficiario','usuarioradica']);

            if(Input::get('html')){
                return view('dompdf.afitramites');
            }

            $pdf = PDF::loadView('dompdf.afitramite', ['afitramite' => $formafiliacion]);
            $pdf->setPaper('letter', 'portrait');
            //echo 'llave: '. $formafiliacion->id; exit;


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
            
            $afAfiliado                     = AfAfiliadoMs::where('consecutivo_afiliado',$beneficiario->id)->orderBy('consecutivo_ns', 'desc')->first();
            $afAfiliado->tipo_traslado      = "MS";
            $afAfiliado->save();
            
            $formularioC    = AsFormafiliacione::find($formafiliacione->id);
            
            $new = new AsFormafiliacione();
            
            foreach($formularioC->getFillable() as $campo) {
                $new->{$campo}          = $formularioC->{$campo};
            }
            
            /*$formularioC    = AsFormafiliacione::find($nuc_familiar->as_formafiliacion_id);
            foreach($formularioC->getFillable() as $campo) {
                $formularioA->{$campo}          = $formularioC->{$campo};
            }*/
            
            $new->as_padre_id           = $formularioC->as_afiliado_id;
            $new->as_parentesco_id      = $beneficiario->pivot->as_parentesco_id;
            $new->as_afiliado_id        = $beneficiario->id;
            $new->rs_ips_id             = $formafiliacione->rs_entidade_id;
            $beneficiario->rs_entidade_id       = $formafiliacione->rs_entidade_id;
            $new->as_regimene_id                = $beneficiario->as_regimene_id;
            $new->as_tipafiliado_id         = $beneficiario->as_tipafiliado_id;
            
            $new->ibc                       = $beneficiario->ibc;
            $new->ficha_sisben               = $beneficiario->ficha_sisben;
            $new->puntaje_sisben            = $beneficiario->puntaje_sisben;
            
            $new->save();
            
            /*$new->nombre1                   = $beneficiario->nombre1;
            $new->nombre2                   = $beneficiario->nombre2;
            $new->apellido1                 = $beneficiario->apellido1;
            $new->apellido2                 = $beneficiario->apellido2;
            $new->identificacion            = $beneficiario->identificacion;
            //Log::info('Beneficiario: ', array($beneficiario));
            $new->gn_tipdocidentidad_id     = $beneficiario->gn_tipdocidentidad_id;
            $new->fecha_expedicion          = $beneficiario->fecha_expedicion;
            $new->fecha_nacimiento          = $beneficiario->fecha_nacimiento;
            $new->gn_sexo_id                = $beneficiario->gn_sexo_id;
            $new->as_padre_id               = $formafiliacione->afiliado->id;
            $new->as_parentesco_id          = $beneficiario->pivot->as_parentesco_id;
            $new->as_tipafiliado_id         = $beneficiario->afiliado->as_tipafiliado_id;
            $new->fecha_radicacion          = $fecha_afiliacion;
            
            $new->as_pagadore_id            = NULL;
            $new->as_afiliado_id            = $beneficiario->id;
            
            $new->save();*/
            $beneficiario->save();
        }
        new GeneradorAfitramites($formafiliacione);
    }

    private function anular(AsFormafiliacione $formafiliacione)
    {
        //Log::info('Info: ', array($formafiliacione->afitramite()));
        if(!empty($formafiliacione->afitramite)){
            foreach ($formafiliacione->afitramite as $afitramite){
                // Log::info('Tramite: ', array($afitramite));
                if(!empty($afitramite->tramite) && $afitramite->tramite->as_bduaarchivo_id){
                    return false;
                }
            }
            $formafiliacione->afitramite()->delete();
        }
        $formafiliacione->estado = 'Anulado';
        $formafiliacione->save();

        return true;
    }

    private function actualizarEstadoAfiliados(AsFormafiliacione $formafiliacione, $estado)
    {
        if(!$formafiliacione->recien_nacido){
            
            $ams = AfAfiliadoMs::where('consecutivo_afiliado',$formafiliacione->afiliado->id)->where('tipo_traslado','S')->first();
            
            Log::info('Objeto Af ms: ', array($ams));
            
            if(!empty($ams)) {
                if($formafiliacione->as_regimene_id == 1) {
                    $ams->tipo_traslado = 'MC';
                } else {
                    $ams->tipo_traslado = 'MS';
                }
                
                $ams->save();
            }
            
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
        if(empty($request->recien_nacido) && empty($request['recien_nacido'])){
            //$todo = $request->all();
            
            $responsable = AsAfiliado::find($request['as_afiliado_id']);
            $responsable->rs_entidade_id        = $request['rs_ips_id'];
            $responsable->save();
            
            return AsFormafiliacione::create($request);
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
            $afiliadoRecienNacido = $this->crearAfiliadoRecienNacido($recienNacido,$responsable,$request);
            AsNucfamiliare::create(
                [
                    'as_beneficiario_id' => $afiliadoRecienNacido->id,
                    'as_parentesco_id' => 2,
                    'rs_entidade_id' => empty($recienNacido['rs_entidade_id']) ? $responsable->rs_entidade_id : $recienNacido['rs_entidade_id'],
                    'as_formafiliacion_id' => $formafiliacion->id
                ]
            );
        }

        return $formafiliacion;

    }

    private function crearAfiliadoRecienNacido($recienNacido, $responsable,$request)
    {

        $estadoActivo = 3;
        $regimenSubsidiado = 2;
        $tipoAfiliadoBeneficiario = 3;
        
        $fecha = str_replace('/', '-', $recienNacido['fecha_expedicion']);
		  $fechaExpedicionFormateada= date("Y-m-d", strtotime($fecha));
		  $fecha = str_replace('/', '-', $recienNacido['fecha_nacimiento']);
		  $fechaNacimientoFormateada = date("Y-m-d", strtotime($fecha));

        $cabeza = $responsable->id;

        if($responsable->cabeza){
            $cabeza = $responsable->cabeza->id;
        }
        
        Log::info('Datos recien nacido', array($recienNacido));
        Log::info('Llave IPS ', array($recienNacido['rs_entidade_id']));
        $tipoIdentificacion     = GnTipdocidentidade::find($recienNacido['gn_tipdocidentidad_id']);
        
        
        $recienNacido = AsAfiliado::create(
            [
                'as_regimene_id' => $responsable->as_regimene_id,
                'nombre1' => $recienNacido['nombre1'],
                'nombre2' => $recienNacido['nombre2'],
                'apellido1' => $recienNacido['apellido1'],
                'apellido2' => $recienNacido['apellido2'],
                'gn_tipdocidentidad_id' => $recienNacido['gn_tipdocidentidad_id'],
                'tipo_identificacion' => $tipoIdentificacion->abreviatura,
                'direccion' => $responsable->direccion,
                'telefono_fijo' => $responsable->telefono_fijo,
                'celular' => $responsable->celular,
                'rs_entidade_id' => $recienNacido['rs_entidade_id'],
                'correo_electronico' => $responsable->correo_electronico,
                'gn_municipio_id' => $responsable->gn_municipio_id,
                'as_etnia_id' => $responsable->as_etnia_id,
                'as_parentesco_id' => 2,
                'cabfamilia_id' => $cabeza,
                'as_condicion_discapacidades_id' => empty($recienNacido['as_condicion_discapacidades_id'])?NULL:$recienNacido['as_condicion_discapacidades_id'],
                'as_pobespeciale_id' => $responsable->as_pobespeciale_id,
                'rs_entidade_id' => $recienNacido['rs_entidade_id'],
                'estado' => $estadoActivo,
                'gn_zona_id' => $responsable->gn_zona_id,
                'gn_vereda_id' => $responsable->gn_vereda_id,
                'fecha_nacimiento' => $fechaNacimientoFormateada,
                'gn_sexo_id' => $recienNacido['gn_sexo_id'],
                'as_tipafiliado_id' => $tipoAfiliadoBeneficiario,
                'ficha_sisben' => $responsable->ficha_sisben,
                'puntaje_sisben' => $responsable->puntaje_sisben,
                'nivel_sisben' => $responsable->nivel_siben,
                'zona_dnp' => $responsable->zona_dnp,
                'gn_barrio_id' => $responsable->gn_barrio_id,
                'fecha_afiliacion' => $fechaNacimientoFormateada,
                'nombre_completo' => '',
                'es_nacimiento' => 1,
                'identificacion' => $recienNacido['identificacion'],
                'fecha_expedicion' => $fechaExpedicionFormateada,
                'gn_paise_id' => $responsable['gn_paise_id'],
            ]
        );
        
        $date = date('Y-m-d');
        $vigencia = TbVigenciaTraslado::where('fecha_inicio', '<=', $date)->where('fecha_fin', '>=', $date)
        ->where('sw_abierto', '=', ESiNo::getIndice(ESiNo::SI)->getId())
        ->where('tipo', '=', ETipoNovedad::getIndice(ETipoNovedad::TRASLADO)->getId())->first();
        
        if (is_null($vigencia) || empty($vigencia)) {
            return response()->json([
                'message' => 'No existe vigencia para crear el afiliado',
                'errors' => 'No existe vigencia para crear el afiliado',
            ], 500);
        }
        
        $afAfiliado = new AfAfiliadoMs();
        
        //$afAfiliado->codigo_entidad           = ;
        //$afAfiliado->tipo_identificacion_padre           = ;
        //$afAfiliado->numero_identificacion_padre           = ;
        
        $afAfiliado->tipo_identificacion_padre = $responsable->tipo_identificacion;
        $afAfiliado->numero_identificacion_padre = $responsable->identificacion;
        
        $afAfiliado->tipo_identificacion = $recienNacido->tipo_identificacion;
        $afAfiliado->numero_identificacion = $recienNacido->identificacion;
        $afAfiliado->primer_apellido = $recienNacido->apellido1;
        $afAfiliado->segundo_apellido = $recienNacido->apellido2;
        $afAfiliado->primer_nombre = $recienNacido->nombre1;
        $afAfiliado->segundo_nombre = $recienNacido->nombre2;
        $afAfiliado->fecha_nacimiento = $recienNacido->fecha_nacimiento;
        $afAfiliado->genero = $recienNacido->gn_sexo_id;
        $afAfiliado->consecutivo_vigencia = $vigencia['consecutivo_vigencia'];
        $afAfiliado->consecutivo_afiliado = $recienNacido->id;
        //$afAfiliado->departamento           = ;
        $afAfiliado->municipio = $recienNacido->gn_municipio_id;
        $afAfiliado->zona_afiliacion = $recienNacido->gn_zona_id;
        $afAfiliado->fecha_afiliacion = $recienNacido->fecha_afiliacion;
        $afAfiliado->tipo_poblacion = $recienNacido->as_pobespeciale_id;
        
        if($responsable->as_regimene_id == 1) {
            $afAfiliado->tipo_traslado = 'MC';
        } else {
            $afAfiliado->tipo_traslado = 'MS';
        }
        
        $afAfiliado->estado_procesado = 0;
        
        $afAfiliado->save();
        
        
        $formafiliacion = AsFormafiliacione::create([
            'as_regimene_id' => $responsable->as_regimene_id,
            'as_tipafiliacione_id' => 1,
            'as_tipafiliado_id' => $responsable->as_tipafiliado_id,
            'as_clasecotizante_id' => $responsable->as_clasecotizante_id,
            'as_afiliado_id' => $recienNacido->id,
            'rs_ips_id' => $responsable->rs_ips_id,
            'estado' => $request->estado,
            'recien_nacido' => $request->recien_nacido,
            'fecha_radicacion' => today()->toDateString()
        ]);

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
