<?php

namespace App\Http\Controllers\Redservicios;

use App\Models\RedServicios\RsGrupservicio;
use App\Models\RedServicios\RsSubgrupservicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class GrupservicioController extends Controller
{
    public function index()
    {

        if(!Cache::has('grupservicios')){
            $grupos = RsGrupservicio::with(['subgrupos.servicios'])->pimp()
                ->orderBy('updated_at','desc')->get();
            Cache::forever('grupservicios', $grupos);
        }
        $grupservicios = Cache::get('grupservicios');

        return RsGrupservicio::with(['subgrupos.servicios'])->pimp()
            ->orderBy('updated_at','desc')->get();

    }
}
