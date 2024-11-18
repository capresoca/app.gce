<?php

namespace App\Http\Controllers\RedServicios;

use App\Capresoca\Aseguramiento\Servicios\AsignaServiciosCSV;
use App\Capresoca\Aseguramiento\Servicios\ValidaAfiliadosCSVExistan;
use App\Http\Requests\RedServicios\AfiliadoservicioResquest;
use App\Http\Requests\RedServicios\AsignaCSVRequest;
use App\Http\Resources\RedServicios\AfiliadoservicioscambResource;
use App\Http\Resources\RedServicios\AfiliadoserviciosinhResource;
use App\Http\Resources\RedServicios\AfiliadoserviciosResource;
use App\Http\Resources\RedServicios\AsignamasivoResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\RedServicios\RsAfiliadoServicio;
use App\Models\RedServicios\RsAsignamasivo;
use App\Http\Controllers\Controller;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class AfiliadoServicioController extends Controller
{
    public function cambio_servicios() {
        return AfiliadoservicioscambResource::collection(
            AsAfiliado::where('as_afiliados.estado',3)
                ->where('as_afiliados.gn_municipio_id','=',Input::get('gn_municipio_id'))
                ->where('as_afiliados.as_regimene_id',Input::get('as_regimene_id'))
                ->join(DB::raw('(SELECT MAX(rs_afiliado_servicios.created_at) as fecha_asignacion,
                    rs_afiliado_servicios.as_afiliado_id, rs_servhabilitados.rs_entidad_id from  rs_afiliado_servicios 
		            JOIN rs_servhabilitados ON rs_afiliado_servicios.rs_servhabilitado_id = rs_servhabilitados.id 
		            WHERE rs_servhabilitados.rs_servicio_id = '.Input::get('rs_servicio_id').' AND
		            rs_servhabilitados.rs_entidad_id = '.Input::get('rs_entidad_id').'
			        GROUP BY rs_afiliado_servicios.as_afiliado_id) as afiliados_tienen'),
                    'as_afiliados.id','=','afiliados_tienen.as_afiliado_id')
                ->limit(1000)
                ->get()
        );
    }

    public function inhabilitados() {
        $retornar_todos = false;
        if(Input::get('rs_servicio_id')) {
            if (count(explode(",", Input::get('rs_servicio_id'))) >= 17) $retornar_todos = true;
        }
        if (Input::get('rs_servicio_id') && !$retornar_todos) {
            return AfiliadoserviciosinhResource::collection(
                AsAfiliado::where('as_afiliados.estado',3)
                    ->whereNull('afiliados_tienen.as_afiliado_id')
                    ->where('as_afiliados.gn_municipio_id','=',Input::get('gn_municipio_id'))
                    ->where('as_afiliados.as_regimene_id',Input::get('as_regimene_id'))
                    ->leftJoin(DB::raw('(SELECT rs_afiliado_servicios.as_afiliado_id from rs_afiliado_servicios 
                    join rs_servhabilitados ON rs_afiliado_servicios.rs_servhabilitado_id = rs_servhabilitados.id 
                    WHERE rs_servhabilitados.rs_servicio_id IN ('.Input::get('rs_servicio_id').')) as afiliados_tienen'),
                        'as_afiliados.id','=','afiliados_tienen.as_afiliado_id')->get()
            );
        }
        return AfiliadoserviciosinhResource::collection(
            AsAfiliado::where('as_afiliados.gn_municipio_id','=',Input::get('gn_municipio_id'))
                ->where('as_afiliados.as_regimene_id',Input::get('as_regimene_id'))
                ->where('as_afiliados.estado',3)
                ->doesnthave('servicios_habilitados')
                ->get()
        );
    }

    public function store(AfiliadoservicioResquest $request)
    {
        try {
            DB::beginTransaction();

            $asigna_masivo = RsAsignamasivo::create($request->all());

            foreach ($request->afiliados as $afiliado) {
                foreach ($request->servicios_habilitados as $servicios_habilitado) {
                    $afiliado_servicio = new RsAfiliadoServicio;
                    $afiliado_servicio->as_afiliado_id = $afiliado['as_afiliado_id'];
                    $afiliado_servicio->rs_servhabilitado_id = $servicios_habilitado['rs_servhabilitado_id'];
                    $afiliado_servicio->rs_asignamasivo_id = $asigna_masivo->id;
                    $afiliado_servicio->save();
                }
            }

            DB::commit();
            return AsignamasivoResource::collection(
                RsAsignamasivo::where('id',$asigna_masivo->id)->with(RsAsignamasivo::allRelations())->get()
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function fromCSV(AsignaCSVRequest $request)
    {
        $fileUploaded =$request->file('csv_afiliados');

        if(!ToolsTrait::checkCollation($request->file('csv_afiliados')->get(),'UTF-8')){
            return response()->json(['message' => 'Favor convertir a codificación UTF-8'],422);
        }

        $asignador = new AsignaServiciosCSV($fileUploaded->getPathname(),$request->servicios_habilitados);
        $asignador->handle();

        if(count($asignador->getRespuesa())) {
            return response()->json($asignador->getRespuesa(),422);
        }

        return response()->json('La asignación fue exitosa',200);
    }

    public function validaCSV(AsignaCSVRequest $request)
    {
        $fileUploaded =$request->file('csv_afiliados');

        if(!ToolsTrait::checkCollation($request->file('csv_afiliados')->get(),'UTF-8')){
            return response()->json(['message' => 'Favor convertir a codificación UTF-8'],422);
        }

        $asignador = new ValidaAfiliadosCSVExistan($fileUploaded->getPathname());
        $asignador->handle();

        if(count($asignador->getRespuesa())){
            return response()->json($asignador->getRespuesa(),422);
        }

        return response()->json('El archivo es valido',200);
    }

}
