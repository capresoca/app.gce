<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\GeneradorTraslatramites;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsFormafiliacione;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Http\Requests\Aseguramiento\FormtrasmovRequest;
use App\Http\Resources\Aseguramiento\FormtrasmovesResource;
use App\Http\Resources\Aseguramiento\FormtrasmovResource;
use App\Models\Aseguramiento\AsNucfamiliare;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use mysql_xdevapi\Exception;

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
//        try {
        DB::beginTransaction();
        $estado_actual = $formtrasmov->estado;
        $formtrasmov->update($request->all());
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
        $this->actualizarEstadoAfiliados($formtrasmov, $estadoRegistrado);
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


        foreach ($formtrasmov->beneficiarios as $beneficiario) {
            $beneficiario->fecha_afiliacion = $fecha_afiliacion;
            $beneficiario->cabfamilia_id = $formtrasmov->afiliado->id;
            $beneficiario->as_parentesco_id = $beneficiario->pivot->as_parentesco_id;
            $beneficiario->save();
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

        if ($afiliadoPagadores->get()->count() > 0) {
            $ultimoPagador = $afiliadoPagadores->first()->pagador()->first();
            $allFromRequest['as_pagadore_id'] = $ultimoPagador->id;
        } else {
            $parentescoCotizante = AsNucfamiliare::where('as_beneficiario_id', $allFromRequest['as_afiliado_id'])
                ->orderBy('id', 'desc')
                ->first();
            if ($parentescoCotizante) {
                $parentesco = $parentescoCotizante->parentesco()->first();
                $cotizantePadre = AsFormafiliacione::find($parentescoCotizante->as_formafiliacion_id);
                $allFromRequest['as_parentesco_id'] = $parentesco->id;
                $allFromRequest['as_padre_id'] = $cotizantePadre->as_afiliado_id;
            }
        }
        return $allFromRequest;
    }
}
