<?php

namespace App\Http\Controllers\Mipres;

use App\Models\Mipres\MpReporteentrega;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class ReportesEntregaController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return Resource::collection(
                MpReporteentrega::with('direccionamiento.afiliado','direccionamiento.entidad')->pimp()->orderBy('id','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(MpReporteentrega::with('direccionamiento.afiliado','direccionamiento.entidad')->pimp()->orderBy('id','desc')
            ->paginate());
    }

}
