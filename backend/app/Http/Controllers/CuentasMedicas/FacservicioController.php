<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\CuentasMedicas\CmFacservicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class FacservicioController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            $referencias = CmFacservicio::with('afiliado')
                ->pimp()->orderBy('updated_at', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($referencias);
        }
        return Resource::collection(CmFacservicio::with('afiliado')->pimp()->orderBy('updated_at', 'desc')->get());

    }

    public function serviciosPrestados($afiliado_id)
    {
        $servicios = CmFacservicio::where('as_afiliado_id', $afiliado_id)->get();

        return new Resource($servicios);

    }


}
