<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use App\Models\TalentoHumano\SCEmpleadoFamilia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class FamiliaController extends Controller
{
    public function index()
    {
        return SCEmpleadoFamilia::where('empleado',Input::get('id'))->orderBy('fecha_nacimiento','desc')->get();
    }

    public function store(Request $request)
    {
        $familia = new SCEmpleadoFamilia();
        $familia->parentesco = $this->parentesco($request->parentesco);
        $familia->grado = $request->grado;
        $familia->nombre = $request->nombre;
        $familia->direccion = $request->direccion;
        $familia->telefono = $request->telefono;
        $familia->fecha_nacimiento = $request->fecha_nacimiento;
        $familia->empleado = $request->empleado;
        $familia->save();
    }

    public function actualizar (Request $request, $id)
    {
        $familia = SCEmpleadoFamilia::find($id);
        $familia->parentesco = $this->parentesco($request->parentesco);
        $familia->grado = $request->grado;
        $familia->nombre = $request->nombre;
        $familia->direccion = $request->direccion;
        $familia->telefono = $request->telefono;
        $familia->fecha_nacimiento = $request->fecha_nacimiento;
        $familia->save();
    }

    public function destroy($id)
    {
        try {
            $familia = SCEmpleadoFamilia::find($id);
            $familia->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function parentesco($valor)
    {
        $tipos = array(
            array("Hijo",1),
            array("Padre",2),
            array("Hermano",3),
            array("Abuelo",4),
            array("Primo",5),
            array("Otros",6)
        );
        
        $longitud = count($tipos);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tipos[$i][0]) {
                $idtipos = $tipos[$i][1];
            }
        }

        return $idtipos;
    }
}
