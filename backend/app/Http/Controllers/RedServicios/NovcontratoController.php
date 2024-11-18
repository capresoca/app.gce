<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Requests\RedServicios\NovcontratoRequest;
use App\Http\Resources\RedServicios\ContratoResource;
use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\RedServicios\RsPlanescobertura;
use App\Models\RedServicios\RsNovcontrato;
use App\RedServicios\RsServhabilitados;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class NovcontratoController extends Controller
{
    public function store(NovcontratoRequest $request, CeProconminuta $contrato)
    {
        try{

            DB::beginTransaction();
            $novedad = $contrato->novedades()->create($request->all());


            $this->modificarContrato($novedad);

            $contrato->load(['entidad','entidad.servicios_generales','entidad.servicios_primarios','novedades']);

            DB::commit();

            return response([
                'contrato'      => new Resource($contrato),
                'contrato_o'    => new ContratoResource($contrato),
            ],201);

        }catch (\Exception $exception)
        {
            return $exception;
        }

    }

    public function update(NovcontratoRequest $request, CeProconminuta $contrato, RsNovcontrato $novcontrato)
    {

        $novcontrato->update($request->all());
        $this->modificarContrato($request, $contrato);

        $contrato->load(['entidad','entidad.servicios_generales','entidad.servicios_primarios', 'novedades']);

        return response([
            'contrato'      => new Resource($contrato),
            'contrato_o'    => new ContratoResource($contrato),
        ],201);
    }

    public function modificarContrato($novedad)
    {
        $contrato = $novedad->contrato;
        if($novedad->tipo === 'Acta Inicio')
        {

            if($novedad->estado === 'Confirmada'){
                $contrato->fecha_acta_inicio = $novedad->fecha;
                $contrato->estado = 'Legalizado';

                $contrato->fecha_finalizacion = Carbon::parse($novedad->fecha)
                    ->addMonths($contrato->plazo_meses)
                    ->addDays($contrato->plazo_dias)->toDateString();

//                //marcar los servicios de la entidad como contratados
//
//                foreach ($contrato->servicios as $servicio) {
//                    $servicio_entidad = $contrato->entidad->servicios_habilitados->where('rs_servicio_id',$servicio->id)->first();
//                }

            }

        }

        if($novedad->tipo === 'Prorroga')
        {

            if($novedad->estado === 'Confirmada'){
                $contrato->modificaciones_plazo += $novedad->plazo_meses || $novedad->plazo_dias ? 1 : 0;
                $contrato->modificaciones_valor += $novedad->valor ? 1 : 0;
                $contrato->valor_total += $novedad->valor;
                $contrato->fecha_finalizacion = Carbon::parse($contrato->fecha_finalizacion)
                                                ->addMonths($novedad->plazo_meses)
                                                ->addDays($novedad->plazo_dias)->toDateString();

            }

        }

        $contrato->save();

    }

}
