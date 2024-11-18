<?php

namespace App\Http\Controllers\AtencionUsuario\CentroRegulador;

use App\Http\Requests\AtencionUsuario\CentroRegulador\ReferenciaRequest;
use App\Http\Resources\AtencionUsuario\CentroRegulador\ReferenciaResource;
use App\Http\Resources\AtencionUsuario\CentroRegulador\ReferenciasResource;
use App\Models\CentroRegulador\AuRefbitacora;
use App\Models\CentroRegulador\AuReferencia;
use App\Models\CentroRegulador\AuReftraslado;
use App\Models\OficinaJuridica\OjTutela;
use App\Traits\ArchivoTrait;
use Barryvdh\DomPDF\Facade as PDF;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ReferenciaController extends Controller
{

    public function index()
    {
//        foreach (AuReferencia::all() as $item) {
//            $item->touch();
//        }
        if(Input::get('per_page')){
            $referencias = AuReferencia::with(
                'afiliado.regimen',
                'regimen',
                'mod_servicio',
                'archivo_uno',
                'archivo_two',
                'tutela',
                'cie10Uno',
                'cie10Two',
                'entidadUno.tercero',
                'entidadTwo.tercero',
                'servicio',
                'usuario')
                ->pimp()->orderBy('orden')->orderBy('updated_at')->paginate(Input::get('per_page'));
            return ReferenciasResource::collection($referencias);
        }
        return ReferenciasResource::collection(
            AuReferencia::with(
                'afiliado.regimen','regimen',
                'mod_servicio','archivo_uno',
                'archivo_two','tutela',
                'cie10Uno','cie10Two',
                'entidadUno.tercero',
                'entidadTwo.tercero',
                'servicio',
                'usuario')
                ->pimp()
                ->orderBy('orden')
                ->orderBy('updated_at')
                ->get());
    }

    public function store(ReferenciaRequest $request)
    {
        try {
            $au_referencia = new AuReferencia();
            $au_referencia->fill($request->except('historia_clinica_id','archivo_uno'));
            $this->subirArchivos($au_referencia, $request);
            $au_referencia->save();
            $au_referencia->load(
                'afiliado.regimen',
                'regimen',
                'mod_servicio',
                'archivo_uno',
                'archivo_two',
                'tutela',
                'cie10Uno',
                'cie10Two',
                'entidadUno.tercero',
                'entidadTwo.tercero',
                'servicio',
                'usuario'
            );
            return new ReferenciasResource($au_referencia);
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'msg' => 'Error en el servidor',
                    'error' => $exception
                ], 500
            );
        }
    }

    public function show(AuReferencia $au_referencia)
    {
        return new ReferenciaResource($au_referencia->load(
            'afiliado.regimen',
            'regimen',
            'mod_servicio',
            'archivo_uno',
            'archivo_two',
            'tutela',
            'cie10Uno',
            'cie10Two',
            'entidadUno.tercero',
            'entidadTwo.tercero',
            'servicio',
            'bitacoras.presentacion.entidad',
            'bitacoras.traslado.entidadOrigen',
            'bitacoras.traslado.entidadDestino',
            'bitacoras.traslado.entidadTransportadora',
            'bitacoras.accion',
            'bitacoras.archivo',
            'cups',
            'usuario'));
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function actualizar (ReferenciaRequest $request, $id) {
        try {
            $au_referencia = AuReferencia::find($id);
            $au_referencia->fill($request->except(['historia_clinica_id','archivo_uno']));
            $au_referencia->save();
            $this->subirArchivos($au_referencia, $request);
//            $file = $request->file('archivo_uno');
//            $fileTwo = $request->hasFile('archivo_two');
            $au_referencia->load(
                'afiliado.regimen',
                'regimen',
                'mod_servicio',
                'archivo_uno',
                'archivo_two',
                'tutela',
                'cie10Uno',
                'cie10Two',
                'entidadUno.tercero',
                'entidadTwo.tercero',
                'servicio',
                'usuario'
            );
            return response()->json(
                [
                    'message' => 'Se ha actualizado correctamente el centro regulador.',
                    'data' => new ReferenciasResource($au_referencia)
                ], 200
            );
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function subirArchivos($au_referencia, $request)
    {
        $path_storage = 'AtencionUsuario/CentroRegulador';

        if ($request->hasFile('archivo_uno')) {
            $archvi_orden = ArchivoTrait::subirArchivo($request->file('archivo_uno'),$path_storage);
            $au_referencia->orden_medica_id = $archvi_orden->id;
        }

        if ($request->hasFile('archivo_two')) {
            $archvi_historia = ArchivoTrait::subirArchivo($request->file('archivo_two'), $path_storage);
            $au_referencia->historia_clinica_id = $archvi_historia->id;
        }

        if (isset($au_referencia->id)) {
            $au_referencia->save();
        }
    }

    public function afiliadoConTutela ($afiliadoId) {
        $ojTutela = OjTutela::where('as_afiliado_id', $afiliadoId)->first();
        return response()->json([
            'msg' => !($ojTutela === null || $ojTutela === '' || $ojTutela === []) ? 'El afiliado presentado tiene tutela.' : null
        ]);
    }


    public function getPdf () {
        try {
            $id = Input::get('id');
            $referencia = AuReferencia::whereId($id)->with([
                'bitacoras.usuario',
                'bitacoras.accion',
                'bitacoras.presentacion.entidad',
                'bitacoras.traslado.entidadTransportadora',
                'bitacoras.traslado.entidadDestino.municipio',
                'afiliado.estado_afiliado',
                'mod_servicio',
                'servicio',
                'regimen',
                'cie10Uno',
                'cie10Two',
                'entidadUno',
                'entidadTwo.municipio',
                'regimen',
                'usuario'
            ])->first();

            if (Input::get('html')) {
                return view('dompdf.referencia');
            }
            //return view('dompdf.referencia', ['referencia' => $referencia]);
            $pdf = PDF::loadView('dompdf.referencia', ['referencia' => $referencia]);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('Radicado Cuentas Medicas No. '.$referencia->consecutivo,['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function byAfiliado($afiliado_id)
    {
        $referencia1 = AuReferencia::with('bitacoras')->where('as_afiliado_id',$afiliado_id)
            ->whereIn('estado',[
        'Iniciada',
        'Presentada',
        'Aceptada',
        'Seleccionada',
        'Traslado Solicitado',
        'Traslado Aceptado',
        'Salida'
        ])->get();

        return ReferenciasResource::collection($referencia1);
    }
    public function traslado($afiliado_id) {
        $referencia1 = AuReferencia::with('bitacoras')
            ->where('as_afiliado_id',$afiliado_id)
            ->where('estado','Salida')
            ->latest()
            ->get()
            ->pluck('bitacoras')
            ->collapse()
            ->where('au_tipaccion_id',8)
            ->first();

        if (!$referencia1) {
            return response()->json('El paciente no cuenta con referencias registradas ó no se ha confirmado el traslado');
        }

        $traslado = AuReftraslado::with('entidadDestino')
            ->where('au_referencia_id',$referencia1->au_referencia_id)
            ->get()
            ->pluck('entidadDestino')
            ->first();

        return response()->json($traslado,200);
    }
}
