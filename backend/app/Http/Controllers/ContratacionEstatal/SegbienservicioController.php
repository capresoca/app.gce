<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Models\ContratacionEstatal\CeSegbienservicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SegbienservicioController extends Controller
{
    public function index () {
        try {
            if (!Cache::has('segmentos')) {
                $segmentos = CeSegbienservicio::with('children.children')->get();
                Cache::forever('segmentos', $segmentos);
            }
            $segmentos = Cache::get('segmentos');
            return $segmentos;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
