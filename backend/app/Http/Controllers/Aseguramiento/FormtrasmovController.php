<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\GeneradorTraslatramites;
use App\Http\Controllers\Controller;
use App\Http\Requests\Aseguramiento\FormtrasmovRequest;
use App\Http\Resources\Aseguramiento\FormtrasmovResource;
use App\Http\Resources\Aseguramiento\FormtrasmovesResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsFormafiliacione;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsNucfamiliare;
use App\Models\Aseguramiento\BDUA\AfAfiliadoMs;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use App\Models\Enums\ESiNo;
use App\Models\Enums\ETipoNovedad;
use App\Models\TbVigenciaTraslado;
use App\Models\Aseguramiento\AsPagadore;
use App\Http\Resources\Aseguramiento\AsAfiliadosResource;
use Illuminate\Http\Request;
use App\Models\Aseguramiento\AsTraslatramite;

class FormtrasmovController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            return FormtrasmovesResource::collection(
                AsFormtrasmov::with(
                    'afiliado',
                    'beneficiarios',
                    'parentesco',
                    'tipoIdentidad',
                    'eps')->pimp()->orderBy('updated_at', 'desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return FormtrasmovResource::collection(AsFormtrasmov::with(
            'afiliado',
            'beneficiarios',
            'parentesco',
            'tipoIdentidad',
            'eps')
            ->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(FormtrasmovRequest $request)
    {
        try {
            DB::beginTransaction();

            $existentes = AsFormtrasmov::where('as_afiliado_id', $request->all()['as_afiliado_id'])
                ->where('estado', 'Registrado')
                ->get();

            if (count($existentes)) {
                abort(409, 'Ya existe un trámite similar en estado registrado sin finalizar.');
            }

            
            $dataRequest = $this->asignarAportante($request->all());
            
            
            $formtrasmov = AsFormtrasmov::create($dataRequest);

            $estadoRegistrado = 2;
            $this->actualizarEstadoAfiliados($formtrasmov, $estadoRegistrado);

            $formtrasmov->load(
                'afiliado.municipio',
                'afiliado.tipo_afiliado',
                'nucleo_familiar.parentesco',
                'nucleo_familiar.ips.tercero',
                'nucleo_familiar.beneficiario',
                'afiliadoPagador.pagador.tercero',
                'afiliadoPagador.arl',
                'pagadores',
                'beneficiarios',
                'eps',
                'parentesco',
                'tipoIdentidad',
                'anexos.anexo',
                'declaraciones.declaracion'
            );

            if ($formtrasmov->estado === 'Radicado') {
                $this->radicar($formtrasmov);
            }

            if($request->tipo == 'Movilidad') {
                $vigencia       = TbVigenciaTraslado::where('fecha_inicio', '<=', date('Y-m-d'))->where('fecha_fin','>=',date('Y-m-d'))
                ->where('sw_abierto','=',ESiNo::getIndice(ESiNo::SI)->getId())
                ->where('tipo','=',ETipoNovedad::getIndice(ETipoNovedad::TRASLADO)->getId())->first();
                
                if($vigencia==null || empty($vigencia)) {
                    abort(409, 'No existe vigencia para crear la novedad y procesarla.');
                }
                
                $afiliado                       = AsAfiliado::find($formtrasmov->as_afiliado_id);
                
                $afAfiliado = new AfAfiliadoMs();
                
                //$afAfiliado->codigo_entidad           = ;
                //$afAfiliado->tipo_identificacion_padre           = ;
                //$afAfiliado->numero_identificacion_padre           = ;
                $afAfiliado->tipo_identificacion            = $afiliado->tipo_identificacion;
                $afAfiliado->numero_identificacion          = $afiliado->identificacion;
                $afAfiliado->primer_apellido                = $afiliado->apellido1;
                $afAfiliado->segundo_apellido               = $afiliado->apellido2;
                $afAfiliado->primer_nombre                  = $afiliado->nombre1;
                $afAfiliado->segundo_nombre                 = $afiliado->nombre2;
                $afAfiliado->fecha_nacimiento               = $afiliado->fecha_nacimiento;
                $afAfiliado->genero                         = $afiliado->gn_sexo_id;
                $afAfiliado->consecutivo_vigencia           = $vigencia->consecutivo_vigencia;
                $afAfiliado->consecutivo_afiliado           = $afiliado->id;
                //$afAfiliado->departamento           = ;
                $afAfiliado->municipio                      = $afiliado->gn_municipio_id;
                $afAfiliado->zona_afiliacion                = $afiliado->gn_zona_id;
                $afAfiliado->fecha_afiliacion               = $afiliado->fecha_afiliacion;
                $afAfiliado->tipo_poblacion                 = $afiliado->as_pobespeciale_id;
                $afAfiliado->tipo_traslado                  = "R";
                $afAfiliado->tipo_cotizante                 = $request->as_clasecotizante_id;
                $afAfiliado->estado_procesado = 0;
                $afAfiliado->save();
            }
            
            DB::commit();

            return response()->json([
                'message' => 'El traslado fue correctamente creado.',
                'formtrasmov' => new FormtrasmovResource($formtrasmov),
                'formtrasmov_o' => new FormtrasmovesResource($formtrasmov),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => $e,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(AsFormtrasmov $formtrasmov)
    {
        return new FormtrasmovResource($formtrasmov->load([
            'parentesco',
            'tipoIdentidad',
            'eps',
            'afiliado.municipio',
            'afiliado.tipo_afiliado',
            'nucleo_familiar.parentesco',
            'nucleo_familiar.ips.tercero',
            'nucleo_familiar.beneficiario',
            'afiliadoPagador.pagador.tercero',
            'afiliadoPagador.arl',
            'beneficiarios',
            'pagadores',
            'anexos.anexo',
            'declaraciones.declaracion']));
    }

    public function update(FormtrasmovRequest $request, AsFormtrasmov $formtrasmov)
    {
        //unset($formtrasmov->fecha_nacimiento_slash);
        Log::info('Objeto retorna: ',array($formtrasmov));
//        try {
        //Log::info('mensaje: ',array($request->tipo));
        DB::update("update as_formtrasmovs SET tipo_traslado = ".($request->tipo_traslado+1)." where id = ".$formtrasmov->id);
        DB::beginTransaction();
        // as_afiliado_id
        if(!empty($request->rs_entidade_id)) {
            $formtrasmov->afiliado->rs_entidade_id      = $request->rs_entidade_id;
            $formtrasmov->afiliado->save();
        }
        
        $estado_actual = $formtrasmov->estado;
         //= NULL; //$formtrasmov->afiliado->fecha_nacimiento;
        //Log::info('Formulario retorna: ',array($formtrasmov->fecha_nacimiento_slash));
        //$requestCopy = $formtrasmov->toArray();
        //unset($requestCopy['fecha_nacimiento_slash']);// = NULL; //$formtrasmov->afiliado->fecha_nacimiento;
        //$formtrasmov->newFromBuilder($requestCopy);
        Log::info('Objeto retorna: ',array($formtrasmov));
        //unset($formtrasmov->afiliado->fecha_nacimiento_slash);
        $formtrasmov->update($request->except(array('tipo_traslado','fecha_efectiva_traslado','fecha_nacimiento_slash')));
        
        // Log::info('mensaje: ',array('',$request->tipo_traslado));
        
        if ($request->estado === 'Radicado' && $estado_actual === 'Registrado') {
            $this->radicar($formtrasmov);

        }

        if ($request->estado === 'Anulado' && ($estado_actual === 'Radicado' || $estado_actual === 'Anulado')) {
            if (!$this->anular($formtrasmov)) {
                return response()->json([
                    'message' => 'No se puede anular el fomulario'
                ], 405);
            }
        }
        
        
        $estadoRegistrado = 2;
        $this->actualizarEstadoAfiliados($formtrasmov, $estadoRegistrado, $request->rs_entidade_id);
        
        $formtrasmov->load(
            'afiliado.municipio',
            'afiliado.tipo_afiliado',
            'nucleo_familiar.parentesco',
            'nucleo_familiar.ips.tercero',
            'nucleo_familiar.beneficiario',
            'afiliadoPagador.pagador.tercero',
            'afiliadoPagador.arl',
            'beneficiarios',
            'pagadores',
            'eps',
            'parentesco',
            'tipoIdentidad',
            'anexos.anexo',
            'declaraciones.declaracion'
        );
        
        $afiliado                       = AsAfiliado::find($formtrasmov->as_afiliado_id);
        if($request->tipo == 'Movilidad' && !empty($request->afiliado_pagador['as_arl_id'])) {
            //Log::info('ARL: ',array($request->afiliado_pagador['as_arl_id']));
            
            $afiliado->estado_traslado      = 'R1';
            $afiliado->as_arl_id            = $request->afiliado_pagador['as_arl_id'];
            
            //Log::info('ARL: ',array($request->afiliado_pagador));
            //Log::info('IBC: ',array($request->afiliado_pagador->ibc));
            $afiliado->ibc                  = $request->afiliado_pagador['ibc'];
            $afiliado->nf_ciiu_id           = $request->nf_ciiu_id;
            
            $afiliado->save();
            
            $afAfiliado                     = AfAfiliadoMs::where('consecutivo_afiliado',$formtrasmov->as_afiliado_id)->where('tipo_traslado','R')->first();
            if(empty($afAfiliado)) {
                $vigencia       = TbVigenciaTraslado::where('fecha_inicio', '<=', date('Y-m-d'))->where('fecha_fin','>=',date('Y-m-d'))
                ->where('sw_abierto','=',ESiNo::getIndice(ESiNo::SI)->getId())
                ->where('tipo','=',ETipoNovedad::getIndice(ETipoNovedad::TRASLADO)->getId())->first();
                
                if($vigencia==null || empty($vigencia)) {
                    return response()->json([
                        'message' => 'No existe vigencia para crear la novedad y procesarla',
                        'errors' => 'No existe vigencia para crear la novedad y procesarla',
                    ], 500);
                }
                
                $afAfiliado = new AfAfiliadoMs();
                
                //$afAfiliado->codigo_entidad           = ;
                //$afAfiliado->tipo_identificacion_padre           = ;
                //$afAfiliado->numero_identificacion_padre           = ;
                $afAfiliado->tipo_identificacion            = $afiliado->tipo_identificacion;
                $afAfiliado->numero_identificacion          = $afiliado->identificacion;
                $afAfiliado->primer_apellido                = $afiliado->apellido1;
                $afAfiliado->segundo_apellido               = $afiliado->apellido2;
                $afAfiliado->primer_nombre                  = $afiliado->nombre1;
                $afAfiliado->segundo_nombre                 = $afiliado->nombre2;
                $afAfiliado->fecha_nacimiento               = $afiliado->fecha_nacimiento;
                $afAfiliado->genero                         = $afiliado->gn_sexo_id;
                $afAfiliado->consecutivo_vigencia           = $vigencia->consecutivo_vigencia;
                $afAfiliado->consecutivo_afiliado           = $afiliado->id;
                //$afAfiliado->departamento           = ;
                $afAfiliado->municipio                      = $afiliado->gn_municipio_id;
                $afAfiliado->zona_afiliacion                = $afiliado->gn_zona_id;
                $afAfiliado->fecha_afiliacion               = $afiliado->fecha_afiliacion;
                $afAfiliado->tipo_poblacion                 = $afiliado->as_pobespeciale_id;
                $afAfiliado->tipo_traslado                  = "R";
                $afAfiliado->estado_procesado = 0;
                $afAfiliado->save();
            }
            
            //Log::info('Llave: ',array($formtrasmov->id));
            $pagador        = AsAfiliadoPagador::where('as_formtrasmov_id',$formtrasmov->id)->first();
            
            if(empty($pagador)) {
                AsAfiliadoPagador::create(
                    [
                        'as_afiliado_id'            => $formtrasmov->as_afiliado_id,
                        'as_pagador_id'             => $request->afiliado_pagador['as_pagadore_id'],
                        'fecha_vinculacion'         => $request->afiliado_pagador['fecha_vinculacion'],
                        'ibc'                       => $request->afiliado_pagador['ibc'],
                        'estado'                    => 'Activo',
                        'as_formtrasmov_id'         => $formtrasmov->id,
                        'tipo_cotizante'            => $request->as_clasecotizante_id,
                        'as_clasecotizante_id'      => $request->as_clasecotizante_id,
                        'as_arl_id'                 => $request->afiliado_pagador['as_arl_id'],
                        'nf_ciiu_id'                => $request->nf_ciiu_id,
                    ]
                    );
                $pagador        = AsAfiliadoPagador::where('as_afiliado_id',$formtrasmov->as_afiliado_id)->first();
            } else {
                $pagador->as_afiliado_id            = $formtrasmov->as_afiliado_id;
                $pagador->as_pagador_id             = $request->afiliado_pagador['as_pagadore_id'];
                $pagador->fecha_vinculacion         = $request->afiliado_pagador['fecha_vinculacion'];
                $pagador->ibc                       = $request->afiliado_pagador['ibc'];
                //$pagador->estado                    = $request->nf_ciiu_id;
                $pagador->as_formtrasmov_id         = $formtrasmov->id;
                $pagador->tipo_cotizante            = $request->as_clasecotizante_id;
                $pagador->as_clasecotizante_id      = $request->as_clasecotizante_id;
                $pagador->as_arl_id                 = $request->afiliado_pagador['as_arl_id'];
                $pagador->nf_ciiu_id                = $request->nf_ciiu_id;
                
                $pagador->save();
            }
            //Log::info('Pagador: ',array($pagador));
            $datosPagador                                   = AsPagadore::find($request->afiliado_pagador['as_pagadore_id']);
            
            /*$pagadorAct        = AsAfiliadoPagador::where('as_formtrasmov_id',$formtrasmov->id)->first();
            $pagadorAct->as_formtrasmov_id                = $formtrasmov->id;
            $pagadorAct->save();*/
            
            $afAfiliado                                     = AfAfiliadoMs::where('consecutivo_afiliado',$formtrasmov->as_afiliado_id)->where('tipo_traslado','R')->first();
            $afAfiliado->ibc                                = $afiliado->ibc;
            $afAfiliado->tipo_identificacion_aportante      = $datosPagador->as_tipaportante_id;
            $afAfiliado->numero_identificacion_aportante    = $datosPagador->identificacion;
            $afAfiliado->consecutivo_aportante              = $datosPagador->id;
            $afAfiliado->save();
        } else {
            Log::info('Informacion: ',array('Entra al traslado'));
            $afAfiliado                     = AfAfiliadoMs::where('consecutivo_afiliado',$formtrasmov->as_afiliado_id)->where('tipo_traslado','S')->first();
            if(empty($afAfiliado)) {
                $vigencia       = TbVigenciaTraslado::where('fecha_inicio', '<=', date('Y-m-d'))->where('fecha_fin','>=',date('Y-m-d'))
                ->where('sw_abierto','=',ESiNo::getIndice(ESiNo::SI)->getId())
                ->where('tipo','=',ETipoNovedad::getIndice(ETipoNovedad::TRASLADO)->getId())->first();
                
                if($vigencia==null || empty($vigencia)) {
                    return response()->json([
                        'message' => 'No existe vigencia para crear la novedad y procesarla',
                        'errors' => 'No existe vigencia para crear la novedad y procesarla',
                    ], 500);
                }
                
                $afAfiliado = new AfAfiliadoMs();

                $afAfiliado->tipo_identificacion            = $afiliado->tipo_identificacion;
                $afAfiliado->numero_identificacion          = $afiliado->identificacion;
                $afAfiliado->primer_apellido                = $afiliado->apellido1;
                $afAfiliado->segundo_apellido               = $afiliado->apellido2;
                $afAfiliado->primer_nombre                  = $afiliado->nombre1;
                $afAfiliado->segundo_nombre                 = $afiliado->nombre2;
                $afAfiliado->fecha_nacimiento               = $afiliado->fecha_nacimiento;
                $afAfiliado->genero                         = $afiliado->gn_sexo_id;
                $afAfiliado->consecutivo_vigencia           = $vigencia->consecutivo_vigencia;
                $afAfiliado->consecutivo_afiliado           = $afiliado->id;
                //$afAfiliado->departamento           = ;
                $afAfiliado->municipio                      = $afiliado->gn_municipio_id;
                $afAfiliado->zona_afiliacion                = $afiliado->gn_zona_id;
                $afAfiliado->fecha_afiliacion               = $afiliado->fecha_afiliacion;
                $afAfiliado->tipo_poblacion                 = $afiliado->as_pobespeciale_id;
                $afAfiliado->tipo_traslado                  = "S";
                $afAfiliado->estado_procesado = 0;
                //Log::info('afiliado guarda: ', array($afAfiliado));
                $afAfiliado->save();
            }
        }
        
        //Log::info('Informacion: ',array('Entra Al commit'));
        DB::commit();
        
        return response()->json([
            'message' => 'El tramite de afiliación fue guardado con exito',
            'formtrasmov' => new FormtrasmovResource($formtrasmov),
            'formtrasmov_o' => new FormtrasmovesResource($formtrasmov),
        ], 202);
//        } catch (\Exception $e){
//            DB::rollBack();
//            return $e;
//        }
    }

    private function radicar(AsFormtrasmov $formtrasmov)
    {
        $fecha_afiliacion = $formtrasmov->fecha_radicacion;
        if (Auth::user()) {
            $formtrasmov->gs_usuconf_id = Auth::user()->id;
            $formtrasmov->save();
        }

        $afiliadoPadre      = AsFormtrasmov::find($formtrasmov->id);

        foreach ($formtrasmov->beneficiarios as $beneficiario) {
            $beneficiario->fecha_afiliacion = $fecha_afiliacion;
            $beneficiario->cabfamilia_id = $formtrasmov->afiliado->id;
            $beneficiario->as_parentesco_id = $beneficiario->pivot->as_parentesco_id;
            $beneficiario->save();
            
            $new = new AsFormtrasmov();
            
            foreach ($afiliadoPadre->getFillable() as $campo) {
                $new->{$campo}          = $afiliadoPadre->{$campo};
            }

            $new->nombre1           = $beneficiario->nombre1;
            $new->nombre2           = $beneficiario->nombre2;
            $new->apellido1         = $beneficiario->apellido1;
            $new->apellido2         = $beneficiario->apellido2;
            $new->identificacion         = $beneficiario->identificacion;
            Log::info('Beneficiario: ', array($beneficiario));
            $new->gn_tipdocidentidad_id         = $beneficiario->gn_tipdocidentidad_id;
            $new->fecha_expedicion         = $beneficiario->fecha_expedicion;
            $new->fecha_nacimiento         = $beneficiario->fecha_nacimiento;
            $new->gn_sexo_id         = $beneficiario->gn_sexo_id;
            $new->as_padre_id         = $formtrasmov->afiliado->id;
            $new->as_parentesco_id         = $beneficiario->pivot->as_parentesco_id;
            
            $new->as_pagadore_id         = NULL;
            
            
            $new->as_afiliado_id      = $beneficiario->id;
            
            $new->save();
        }
        new GeneradorTraslatramites($formtrasmov);
    }

    private function anular(AsFormtrasmov $formtrasmov)
    {
        //Verificar que no tenga afitramites con archivoBDUA creado
        if ($formtrasmov->traslatramites) {
            foreach ($formtrasmov->traslatramites as $traslatramite) {
                if ($traslatramite->tramite->as_bduaarchivo_id) {
                    return false;
                }
            }
            $formtrasmov->traslatramites()->delete();
            $formtrasmov->estado = 'Anulado';
            $formtrasmov->save();
        }

        $estadoProcesoRegistro = 1;
        $this->actualizarEstadoAfiliados($formtrasmov, $estadoProcesoRegistro);

        return true;
    }
    
    public function anularTraslado(Request $request)
    {
        $formtrasmov        = AsFormtrasmov::find($request->id);
        //Verificar que no tenga afitramites con archivoBDUA creado
        //Log::info('traslado llega: ', array($formtrasmov));
        //exit;
        //Log::info('tramites: ',array($formtrasmov->traslatramites));
        //exit;
        $tramites       = AsTraslatramite::where('as_formtrasmov_id',$request->id)->get();
        
        if (!empty($tramites)) {
            Log::info('ver: ', array($tramites));
            //if(!empty($tramites)) {
            foreach ($tramites as $traslatramite) {
                if(!is_bool($traslatramite)) {
                    if ($traslatramite->tramite->as_bduaarchivo_id) {
                        return false;
                    }
                }
            }
            foreach ($tramites as $tra) {
                $tra->delete();
            }
            //}
        }

        $formtrasmov->estado = 'Anulado';
        $formtrasmov->save();
        
        $estadoProcesoRegistro = 1;
        $this->actualizarEstadoAfiliados($formtrasmov, $estadoProcesoRegistro);
        
        //return true;
    }

    private function actualizarEstadoAfiliados(AsFormtrasmov $formtrasmov, $estado)
    {
        
        if ($formtrasmov->afiliado->estado === 1) {
            $formtrasmov->afiliado->estado = $estado;
            $formtrasmov->afiliado->save();
        }

        

        foreach ($formtrasmov->beneficiarios as $beneficiario) {
            if ($beneficiario->estado === 1) {
                $beneficiario->estado = $estado;
                $beneficiario->save();
            }
        }

    }

    public function getPdfTraslado()
    {
        try {
            $id = Input::get('id');
            $formtrasmov = AsFormtrasmov::whereId($id)->with(
                [
                    'parentesco',
                    'tipoIdentidad',
                    'eps',
                    'afiliado.municipio',
                    'afiliado.ips',
                    'afiliado.tipo_afiliado',
                    'nucleo_familiar.parentesco',
                    'nucleo_familiar.ips.tercero',
                    'nucleo_familiar.beneficiario',
                    'afiliadoPagador.pagador.tercero',
                    'afiliadoPagador.arl',
                    'beneficiarios',
                    'pagadores',
                    'anexos.anexo',
                    'declaraciones.declaracion'
                ]
            )->first();

            if (Input::get('html')) {
                return view('dompdf.afitramitetraslado');
            }
            setlocale(LC_ALL, "es_ES");

            $pdf = PDF::loadView('dompdf.afitramitetraslado', ['afitramite' => $formtrasmov]);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('Tramite de Traslado ' . $formtrasmov->identificacion, ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function asignarAportante($allFromRequest)
    {
//        $allFromRequest['as_afiliado_id'] = '10590'; // AFILIADO CON EMPLEADOR, COMENTAR PARA USAR DATO DE FRONTEND
//        $allFromRequest['as_afiliado_id'] = '204689'; // AFILIADO BENEFICIARIO CON NUCLEO FAMILIAR, COMENTAR PARA USAR DATO DE FRONTEND
        $afiliadoPagadores = AsAfiliadoPagador::where('as_afiliado_id', $allFromRequest['as_afiliado_id'])
            ->orderBy('id', 'desc');
            //Log::info('cantidad: ',array($afiliadoPagadores->get()->count()));
        if ($afiliadoPagadores->get()->count() > 0) {
            $ultimoPagador = $afiliadoPagadores->first()->pagador()->first();
            $allFromRequest['as_pagadore_id'] = $ultimoPagador->id;
        } else {
            $parentescoCotizante    = AsNucfamiliare::where('as_beneficiario_id', $allFromRequest['as_afiliado_id'])
                ->orderBy('id', 'desc')
                ->first();
            if ($parentescoCotizante) {
                $parentesco         = $parentescoCotizante->parentesco()->first();
                $cotizantePadre     = AsFormafiliacione::find($parentescoCotizante->as_formafiliacion_id);
                $idPadre            = NULL;
                if($cotizantePadre==NULL) {
                    $afiliado       = AsAfiliado::find($allFromRequest['as_afiliado_id']);
                    $idPadre        = $afiliado->cabfamilia_id;
                } else {
                    $idPadre        = $cotizantePadre->as_afiliado_id;
                }

                $allFromRequest['as_parentesco_id']     = $parentesco->id;
                $allFromRequest['as_padre_id']          = $idPadre;
            }
        }
        return $allFromRequest;
    }
}