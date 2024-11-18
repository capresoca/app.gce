<?php

namespace App\Http\Controllers\Aseguramiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Resources\AtencionUsuario\LiLicenciaResource;
use App\Models\LiGestiones\LiLicencia;

class LiLicenciaController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            // \Log::debug(print_r(LiLicenciaResource::collection(LiLicencia::with('rsentidades')->pimp()->orderBy('consecutivo_licencia', 'desc')->paginate(Input::get('per_page'))), true));

            return LiLicenciaResource::collection(LiLicencia::with('rsentidades', 'aportante','medicos')->pimp()->orderBy('consecutivo_licencia', 'desc')->paginate(Input::get('per_page'))); 
        }
        return LiLicenciaResource::collection(LiLicencia::with('rsentidades')->pimp()->limit(100)->orderBy('consecutivo_licencia', 'desc')->get());
    }
}
