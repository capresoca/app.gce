<?php

namespace App\Http\Controllers\RedServicios;

use App\Models\RedServicios\RsCupsgrupo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CupsGrupoController extends Controller
{
    public function index () {
        try {
            if (!Cache::has('grupos')) {
                $grupos = RsCupsgrupo::with('children.children')->get();
                Cache::forever('grupos', $grupos);
            }
            $grupos = Cache::get('grupos');
            return $grupos;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
