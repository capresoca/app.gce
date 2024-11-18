<?php

namespace App\Http\Controllers\AtencionUsuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\LiGestiones\LiLicencia;
use App\Models\LiGestiones\LiLicenciaSoporte;
use App\Models\AtencionUsuario\TbProcesoAdministrativoDetalle;
use App\Models\General\GnMunicipio;
use App\Models\Aseguramiento\AsPagadore;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GestionIncapacidadController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $afpagador = AsAfiliadoPagador::pimp()->orderBy('id', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($afpagador);
        }
        return Resource::collection(TbProfesion::pimp()->orderBy('profesion', 'desc')->get());
    }

    public function asAfiliadoPagadorActivo()
    {
        return AsAfiliadoPagador::join('as_pagadores', 'as_pagadores.id', '=', 'as_afiliado_pagador.as_pagador_id')
                                ->where('estado','=','Activo')
                                ->where('as_afiliado_id','=',Input::get('id'))
                                ->select('as_pagadores.id', 'as_pagadores.identificacion as idaportante', 'as_pagadores.razon_social  as aportante', 'fecha_vinculacion', 'fecha_fin_vinculacion')
                                ->get();
    }

    public function verificarLicencia(Request $request)
    {
        \Log::info($request);
        $arrayContar = [];
        $lengthRequest = count($request->fechas_aportantes);
        for ($i=0; $i < $lengthRequest; $i++) { 
            \Log::info('ciclo '.$i);
            // \Log::info('id aportantes: '. $request->ids_aportantes[$i]);
            // \Log::info('fecha aportantes: '. $request->fechas_aportantes[$i]);
            $contar = DB::select( DB::raw("
            select count(c.consecutivo_licencia) as cantidad
            from li_licencias c
            where c.estado_licencia in (1, 4)
            and c.fecha_inicio <= '".$request->fecha."'
            and c.fecha_fin >= '".$request->fecha."'
            and c.consecutivo_afiliado = '".$request->consecutivo_afiliado."'
            and c.consecutivo_aportante = '".$request->ids_aportantes[$i]."'
             "));
            if ($contar[0]->cantidad === 0) {
                \Log::info('entro a if');

            } else {
                \Log::info('entro a else');
                // return response()->json([
                //     'message' => 'Ya existe una solicitud para esta incapacidad con las mismas fechas.',
                //     'alerta' => 'Para el aportante con identificación '.$request->identificaciones_aportantes[$i].', ya existe una solicitud de incapacidad en la fecha indicada.',
                //     'contador' => $contar[0]->cantidad
                // ]);
                $arrayContar[$i] = [
                    'alerta' => 'Para el aportante con identificación '.$request->identificaciones_aportantes[$i].', ya existe una solicitud de incapacidad en la fecha indicada.'
                ];
            }
        }
        \Log::info('arraycontar: '. print_r($arrayContar, true));


        \Log::info('contar es: '. print_r($contar, true));
        \Log::info('contar es: '. $contar[0]->cantidad);

        return response()->json([
            'message' => 'Successful',
            // 'contador' => $contar[0]->cantidad
            'alertas' => $arrayContar
        ]);
    }

    public function getSoportes ()
    {
        $soportes = TbProcesoAdministrativoDetalle::
                    join('tb_proceso_administrativo_encabezados', 'tb_proceso_administrativo_encabezados.consecutivo_proceso', '=', 'tb_proceso_administrativo_detalles.consecutivo_proceso')
                  ->join('tb_tipo_soportes', 'tb_tipo_soportes.consecutivo_soporte', '=', 'tb_proceso_administrativo_detalles.consecutivo_soporte')
                  ->where('tb_proceso_administrativo_encabezados.proceso', Input::get('idtipo'))
                  ->where('tb_proceso_administrativo_detalles.sw_activo', '1')
                  ->select('tb_tipo_soportes.descripcion', 'tb_proceso_administrativo_detalles.sw_obligatorio', 'tb_tipo_soportes.observacion')
                  ->get();
        \Log::info('los soportes: '.$soportes);
        return $soportes;
    }

    public function radicar (Request $request)
    {
        $semanaGestacion = '';
        $nroHijos = '';
        $fechaParto = '';
        if ($request->tipo === '6' || $request->tipo === '7' || $request->tipo === '8') {
            if ($request->tipo === '7') {
                $semanaGestacion = 40;
            } else {
                $semanaGestacion = 0;
            }

            if ($request->tipo === '7') {
                $nroHijos = 1;
            } else {
                $nroHijos = 0;
            }

            if ($request->tipo === '8') {
                $fechaParto = $request->fecha;
            } else {
                $fechaParto = '';
            }
        }
        $afiliado = AsAfiliado::where('id', $request->consecutivo_afiliado)->get();
        $departamento = GnMunicipio::where('id', $afiliado[0]->gn_municipio_id)->get();
        $aportante = AsPagadore::leftJoin('as_tipaportantes', 'as_pagadores.as_tipaportante_id', '=', 'as_tipaportantes.id')->where('as_pagadores.id', $request->ids_aportantes[0])->get();

        for ($i=0; $i < count($request->ids_aportantes); $i++) { 
            $licencia = new LiLicencia();
            $licencia->consecutivo_afiliado = $request->consecutivo_afiliado;
            $licencia->consecutivo_ips = $request->prestador;
            $licencia->consecutivo_medico= $request->registro_profesional;
            $licencia->nombre_profesional = $request->nombre_profesional;
            $licencia->diagnostico_principal = $request->diagnostico_principal;
            $licencia->tipo_incapacidad = $request->tipo;
            $licencia->fecha_inicio = $request->fecha;
            $licencia->fecha_fin = $request->fecha_fin;
            $licencia->fecha_expedicion = $request->fecha_expedicion;
            $licencia->numero_dias_incapacidad = $request->dias;
            $licencia->fecha_parto = $fechaParto;
            $licencia->semanas_gestacion = $semanaGestacion;
            $licencia->numero_hijos = $nroHijos;
            $licencia->estado_licencia = 1;
            $licencia->fecha_grabado =  date("Y-m-d");
            $licencia->usuario_grabado = \Auth::user()->identification; //antes 'usertemporal';
            $licencia->tipo_identificacion_afiliado = $afiliado[0]->tipo_identificacion;
            $licencia->numero_identificacion_afiliado = $afiliado[0]->identificacion;
            $licencia->estado_afiliado = $afiliado[0]->estado;
            $licencia->estado_traslado = $afiliado[0]->estado_traslado == null ? '' : $afiliado[0]->estado_traslado;
            $licencia->tipo_regimen_afiliado = $afiliado[0]->as_regimene_id;
            $licencia->municipio_afiliado = $afiliado[0]->gn_municipio_id;
            $licencia->departamento_afiliado = $departamento[0]->gn_departamento_id;
            $licencia->primer_nombre = $afiliado[0]->nombre1;
            $licencia->segundo_nombre = $afiliado[0]->nombre2;
            $licencia->primer_apellido = $afiliado[0]->apellido1;
            $licencia->segundo_apellido = $afiliado[0]->apellido2;
            $licencia->registro_profesional = $request->registro_profesional;
            $licencia->tipo_cotizante = $afiliado[0]->tipo_cotizante;
            $licencia->consecutivo_aportante = $request->ids_aportantes[$i];
            $licencia->consecutivo_tutela = $request->tutela;
            $licencia->email = $afiliado[0]->correo_electronico;
            $licencia->telefono = $afiliado[0]->telefono_fijo;
            $licencia->celular = $afiliado[0]->celular;

            $licencia->tipo_identificacion_aportante = $aportante[0]->nombre;
            $licencia->numero_identificacion_aportante =  $aportante[0]->identificacion;
            $licencia->save();

            // \Log::info('el archivo--'.$request->file('archivos'));
            // \Log::info('el archivos--'.print_r($request->archivos, true));       
            $archivos = $request->file('archivos');
            if($request->hasFile('archivos'))
            {
                $contador = 0;
                foreach ($archivos as $archivo) {
                    $licenciaSoporte = new LiLicenciaSoporte();
                    $licenciaSoporte->consecutivo_licencia = $licencia->consecutivo_licencia;
                    $licenciaSoporte->soporte = $request->descripcionSoporte[$contador];
                    $licenciaSoporte->save();
                    $nombre = $licencia->consecutivo_licencia.'-'.$licenciaSoporte->consecutivo_soporte.'-'.$archivo->getClientOriginalName();
                    // $nombre = $archivo->getClientOriginalName();
                    \Log::info('el nombre archivo--'.$nombre);
                    \Storage::disk('local')->put($nombre,  \File::get($archivo));
                    $url = 'app/soportes_incapacidades/'.$nombre;
                    $editarSoporte = LiLicenciaSoporte::find($licenciaSoporte->consecutivo_soporte);
                    $editarSoporte->url = $url;
                    $editarSoporte->save();
                    $contador ++;
                }
            }

        }

    }
}
