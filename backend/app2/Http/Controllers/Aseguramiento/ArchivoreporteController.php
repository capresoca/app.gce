<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Aseguramiento\GeneradorReporte;
use App\Http\Requests\Aseguramiento\ArchivoreporteRequest;
use App\Http\Resources\Aseguramiento\ArchivoreporteResource;
use App\Http\Resources\Aseguramiento\AsAfiliadoResource;
use App\Http\Resources\Aseguramiento\AsAfiliadosResource;
use App\Models\Aseguramiento\AsArchivoreporte;
use App\Models\Aseguramiento\AsTramite;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ArchivoreporteController extends Controller
{
    public function index(){
        if(Input::get('per_page')){
            return ArchivoreporteResource::collection(
                AsArchivoreporte::with('novedades','afiliaciones')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ArchivoreporteResource::collection(
            AsArchivoreporte::pimp()->orderBy('updated_at','desc')->get());

    }

    public function store(ArchivoreporteRequest $request){
        try{

            DB::BeginTransaction();
            $registro_reporte = AsArchivoreporte::create($request->all());

            $tramites = AsTramite::where('tipo_tramite',$registro_reporte->tipo)
                ->where('as_archivoreporte_id',null)->get();


            foreach ($tramites as $key=>$tramite){
                $tramite->as_archivoreporte_id = $registro_reporte->id;
                $tramite->consecutivo_reporte = $key + 1;
                $tramite->save();
            }

            if($registro_reporte->estado === 'Confirmado'){
                new GeneradorReporte($registro_reporte);
            }

            DB::commit();
            return new ArchivoreporteResource($registro_reporte);

        }catch (\Exception $e){
            DB::rollback();
            return $e;
        }

    }

    public function show(AsArchivoreporte $archivoreporte)
    {

        $archivoreporte->load('tramites');

        return new ArchivoreporteResource($archivoreporte);
    }

    public function download(AsArchivoreporte $archivoreporte){
        return ArchivoTrait::download($archivoreporte->archivo->ruta,$archivoreporte->archivo->nombre,'attachment');
    }


    public function update(ArchivoreporteRequest $request, AsArchivoreporte $archivoreporte){
        try{
            if($archivoreporte->estado === 'Confirmado'){
                return response()->json([
                    'message' => 'No se puede editar este archivo porque se encuentra confirmado'
                ], 409);
            }
            $archivoreporte->update($request->except('tramites'));
            foreach ($archivoreporte->tramites as $tramite) {
                $tramite->as_archivoreporte_id = null;
                $tramite->consecutivo_reporte = null;
                $tramite->save();
            }

            foreach ($request->tramites as $key=>$tramite_id){
                $tramite = AsTramite::find($tramite_id);
                $tramite->as_archivoreporte_id = $archivoreporte->id;
                $tramite->consecutivo_reporte = $key + 1;
                $tramite->save();
            }

            if($archivoreporte->estado === 'Confirmado'){
                new GeneradorReporte($archivoreporte);
            }

            DB::commit();
            return new ArchivoreporteResource($archivoreporte);
        } catch (\Exception $e){
            return $e;
        }


    }


}
