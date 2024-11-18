<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Requests\Pagos\PgConfiguracionRequest;
use App\Http\Resources\Pagos\ConfiguracionesResource;
use App\Models\Pagos\PgConfiguracione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfiguracionController extends Controller
{
    public function index(){
        $configuracion = PgConfiguracione::with('tipcomp_ndebito','tipcomp_ncredito','tipcomp_traslado','tipcomp_cxp','niif_descuento','niif_aprovechamiento','rangos')->first();
        if($configuracion){
            return new ConfiguracionesResource($configuracion);
        } else {
            return response()->json([
                'message' => 'Aun no se ha realizado la configuración de pagos'
            ]);
        }
    }

    public function update(PgConfiguracionRequest $request, PgConfiguracione $pg_configuracione){
        $pg_configuracione->update($request->all());
        $pg_configuracione->load('tipcomp_ndebito','tipcomp_ncredito','tipcomp_traslado','tipcomp_cxp','niif_descuento','niif_aprovechamiento','rangos');
        return new ConfiguracionesResource($pg_configuracione);
    }

    public function store(PgConfiguracionRequest $request){
        $configuracion = PgConfiguracione::create($request->all());
        $configuracion->load('tipcomp_ndebito','tipcomp_ncredito','tipcomp_traslado','tipcomp_cxp','niif_descuento','niif_aprovechamiento','rangos');
        return new ConfiguracionesResource($configuracion);
    }
}
