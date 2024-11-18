<?php

namespace App\Http\Controllers\AtencionUsuario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TalentoHumano\TbDiaFestivo;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Http\Resources\AtencionUsuario\TbDiasFestivosResource;

class TBDiaFestivoController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {

            return TbDiasFestivosResource::collection(TbDiaFestivo::pimp()->orderBy('descripcion', 'desc')->paginate(Input::get('per_page'))); 
        }
        // return LiLicenciaResource::collection(LiLicencia::with('rsentidades')->pimp()->limit(100)->orderBy('consecutivo_licencia', 'desc')->get());
        return TbDiasFestivosResource::collection(TbDiaFestivo::pimp()->orderBy('descripcion', 'desc')->paginate(Input::get('per_page'))); 
    }

    public function store(Request $request)
    {
        $festivo = new TbDiaFestivo();
        $festivo->descripcion = $request->descripcion;
        $festivo->fecha = $request->fecharaw;
        $festivo->save();
    }

    public function actualizar (Request $request, $id)
    {
        $festivo = TbDiaFestivo::find($id);
        $festivo->descripcion = $request->descripcion;
        $festivo->fecha = $request->fecharaw;
        $festivo->save();
    }

    public function destroy($id)
    {
        try {
            $festivo = TbDiaFestivo::find($id);
            $festivo->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
