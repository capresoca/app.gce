<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Resources\Aseguramiento\TipnovedadResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsTipnovedade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipNovedadController extends Controller
{
    public function index(AsAfiliado $afiliado){

        $tipNovedadesQuery = AsTipnovedade::where('regimen', 'like', '%'.$afiliado->as_regimene_id.'%')
            ->where('tip_afiliado','like', '%'.$afiliado->as_tipafiliado_id.'%');


        if(!$afiliado->cabfamilia_id || $afiliado->as_tipafiliado_id <> 1){
            $tipNovedadesQuery->where('codigo', '<>','N05');
        }

        if(!$afiliado->cabfamilia_id && $afiliado->beneficiarios->count()){
            $tipNovedadesQuery->where('codigo', '<>','N07');
        }

        if(!$afiliado->aportantes->count()){
            $tipNovedadesQuery->where('codigo', '<>','N10');
        }

        return TipnovedadResource::collection(
            $tipNovedadesQuery->with('campos')->get()
        );
    }
}
