<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use App\Models\TalentoHumano\SCEmpleadoLaboral;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class LaboralController extends Controller
{
    public function index()
    {
        return SCEmpleadoLaboral::where('empleado',Input::get('id'))->orderBy('fecha_ingreso','desc')->get();
    }

    public function store(Request $request)
    {
        $laboral = new SCEmpleadoLaboral();
        $laboral->empresa = $request->empresa;
        $laboral->cargo = $request->cargo;
        $laboral->fecha_ingreso = $request->fecha_ingreso;
        $laboral->fecha_retiro = $request->fecha_retiro;
        $laboral->telefono = $request->telefono;
        $laboral->direccion = $request->direccion;
        $laboral->empleado = $request->empleado;
        $laboral->save();
    }

    public function actualizar (Request $request, $id)
    {
        $laboral = SCEmpleadoLaboral::find($id);
        $laboral->empresa = $request->empresa;
        $laboral->cargo = $request->cargo;
        $laboral->fecha_ingreso = $request->fecha_ingreso;
        $laboral->fecha_retiro = $request->fecha_retiro;
        $laboral->telefono = $request->telefono;
        $laboral->direccion = $request->direccion;
        $laboral->save();
    }

    public function destroy($id)
    {
        try {
            $laboral = SCEmpleadoLaboral::find($id);
            $laboral->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
