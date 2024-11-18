<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TalentoHumano\TbProfesion;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class TbProfesionController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $profesion = TbProfesion::pimp()->orderBy('profesion', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($profesion);
        }
        return Resource::collection(TbProfesion::pimp()->orderBy('profesion', 'desc')->get());
    }

    public function store(Request $request)
    {
        $profesion = new TbProfesion();
        $profesion->descripcion = $request->descripcion;
        $profesion->save();
    }

    public function actualizar (Request $request, $id)
    {
        $profesion = TbProfesion::find($id);
        $profesion->descripcion = $request->descripcion;
        $profesion->save();
    }

    public function destroy($id)
    {
        try {
            $profesion = TbProfesion::find($id);
            $profesion->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
