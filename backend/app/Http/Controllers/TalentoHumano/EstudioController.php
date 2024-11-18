<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use App\Models\TalentoHumano\SCEmpleadoEstudio;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class EstudioController extends Controller
{
    public function index()
    {
        return SCEmpleadoEstudio::where('empleado',Input::get('id'))->orderBy('fecha','desc')->get();
    }

    public function store(Request $request)
    {
        $estudio = new SCEmpleadoEstudio();
        $estudio->establecimiento = $request->establecimiento;
        $estudio->titulo = $request->titulo;
        $estudio->fecha = $request->fecha;
        $estudio->empleado = $request->empleado;
        $estudio->save();
    }

    public function actualizar (Request $request, $id)
    {
        $estudio = SCEmpleadoEstudio::find($id);
        $estudio->establecimiento = $request->establecimiento;
        $estudio->titulo = $request->titulo;
        $estudio->fecha = $request->fecha;
        $estudio->save();
    }

    public function destroy($id)
    {
        try {
            $estudio = SCEmpleadoEstudio::find($id);
            $estudio->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
