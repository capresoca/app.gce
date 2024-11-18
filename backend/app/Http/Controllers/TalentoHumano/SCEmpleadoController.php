<?php

namespace App\Http\Controllers\TalentoHumano;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TalentoHumano\SCEmpleado;
use App\Models\TalentoHumano\SCEmpleadoEstudio;
use App\Models\TalentoHumano\SCEmpleadoFamilia;
use App\Models\TalentoHumano\SCEmpleadoLaboral;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SCEmpleadoController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $empleado = SCEmpleado::pimp()->orderBy('empleado', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($empleado);
        }
        return Resource::collection(SCEmpleado::pimp()->orderBy('empleado', 'desc')->get());
    }

    public function store(Request $request)
    {
        $empleado = new SCEmpleado();

        // \Log::info(print_r($request[0]['tipo_identificacion'], true));
        $empleado->fill(collect($request[0])->except('tipo_identificacion','sw_vivienda_propia','sexo','grupo_sanguineo','rh','estado_civil','sw_usa_gafa','clase_cuenta','sw_nomina')->toArray());
        $empleado->tipo_identificacion = $this->tipoDocumento($request[0]['tipo_identificacion']);
        $empleado->sw_vivienda_propia = $this->dicotomia($request[0]['sw_vivienda_propia']);
        $empleado->sexo = $this->genero($request[0]['sexo']);
        $empleado->grupo_sanguineo = $this->sangre($request[0]['grupo_sanguineo']);
        $empleado->rh = $this->rhs($request[0]['rh']);
        $empleado->estado_civil = $this->estadoCivil($request[0]['estado_civil']);
        $empleado->sw_usa_gafa = $this->dicotomia($request[0]['sw_usa_gafa']);
        $empleado->clase_cuenta = $this->claseCuenta($request[0]['clase_cuenta']);
        $empleado->sw_nomina = $this->dicotomia($request[0]['sw_nomina']);

        $empleado->save();

        $this->storeEmpleadoFamilia($request[1], $empleado->empleado);
        $this->storeEmpleadoExperiencia($request[2], $empleado->empleado);
        $this->storeEmpleadoEducacion($request[3], $empleado->empleado);

        return response()->json([
            'message' => 'Creado con exito',
            'data' => SCEmpleado::where('empleado',$empleado->empleado)->first()
        ]);
    }

    public function actualizar (Request $request, $id)
    {
        $empleado = SCEmpleado::find($id);
        $empleado->fill(collect($request[0])->except('tipo_identificacion','sw_vivienda_propia','sexo','grupo_sanguineo','rh','estado_civil','sw_usa_gafa','clase_cuenta','sw_nomina')->toArray());
        $empleado->tipo_identificacion = $this->tipoDocumento($request[0]['tipo_identificacion']);
        $empleado->sw_vivienda_propia = $this->dicotomia($request[0]['sw_vivienda_propia']);
        $empleado->sexo = $this->genero($request[0]['sexo']);
        $empleado->grupo_sanguineo = $this->sangre($request[0]['grupo_sanguineo']);
        $empleado->rh = $this->rhs($request[0]['rh']);
        $empleado->estado_civil = $this->estadoCivil($request[0]['estado_civil']);
        $empleado->sw_usa_gafa = $this->dicotomia($request[0]['sw_usa_gafa']);
        $empleado->clase_cuenta = $this->claseCuenta($request[0]['clase_cuenta']);
        $empleado->sw_nomina = $this->dicotomia($request[0]['sw_nomina']);     

        $empleado->save();

        return response()->json([
                'message' => 'Editado con exito',
                'data' => $empleado
            ]);
    }

    public function destroy($id)
    {
        try {
            $familia = SCEmpleadoFamilia::where('empleado',$id)->get();
            if (!empty($familia)) {
                foreach ($familia as $key => $value) {
                    \Log::debug('id para borrar '.$value->empleado_familia);
                    $this->destroyFamilia($value->empleado_familia);
                }
            }

            $estudio = SCEmpleadoEstudio::where('empleado',$id)->get();
            if (!empty($estudio)) {
                foreach ($estudio as $key => $value) {
                    $this->destroyEstudio($value->empleado_estudio);
                }
            }

            $laboral = SCEmpleadoLaboral::where('empleado',$id)->get();
            if (!empty($laboral)) {
                foreach ($laboral as $key => $value) {
                    $this->destroyLaboral($value->empleado_laboral);
                }
            }

            $empleado = SCEmpleado::find($id);
            $empleado->delete();

            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function storeEmpleadoFamilia($arrayFamilia,$idEmpleado)
    {
        foreach ($arrayFamilia as $key => $value) {
            foreach ($value as $key => $family) {
                $empleado_familia = new SCEmpleadoFamilia();
                $empleado_familia->parentesco = $this->parentesco($family['parentesco']);
                $empleado_familia->grado = $family['grado'];
                $empleado_familia->nombre = $family['nombre'];
                $empleado_familia->direccion = $family['direccion'];
                $empleado_familia->telefono = $family['telefono'];
                $empleado_familia->fecha_nacimiento = $family['fecha_nacimiento'];
                $empleado_familia->empleado = $idEmpleado;
                $empleado_familia->save();
            }            
        }
    }

    public function storeEmpleadoEducacion($arrayEducacion,$idEmpleado)
    {
        foreach ($arrayEducacion as $key => $value) {
            foreach ($value as $key => $study) {
                $empleado_educacion = new SCEmpleadoEstudio();
                $empleado_educacion->establecimiento = $study['establecimiento'];
                $empleado_educacion->titulo = $study['titulo'];
                $empleado_educacion->fecha = $study['fecha'];
                $empleado_educacion->empleado = $idEmpleado;
                $empleado_educacion->save();
            }            
        }
    }

    public function storeEmpleadoExperiencia($arrayExperiencia,$idEmpleado)
    {
        foreach ($arrayExperiencia as $key => $value) {
            foreach ($value as $key => $experience) {
                $empleado_laboral = new SCEmpleadoLaboral();
                $empleado_laboral->empresa = $experience['empresa'];
                $empleado_laboral->cargo = $experience['cargo'];
                $empleado_laboral->fecha_ingreso = $experience['fecha_ingreso'];
                $empleado_laboral->fecha_retiro = $experience['fecha_retiro'];
                $empleado_laboral->direccion = $experience['direccion'];
                $empleado_laboral->telefono = $experience['telefono'];
                $empleado_laboral->empleado = $idEmpleado;
                $empleado_laboral->save();
            }            
        }
    }

    public function destroyFamilia($id)
    {
        try {
            $empleado = SCEmpleadoFamilia::find($id);
            $empleado->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroyEstudio($id)
    {
        try {
            $empleado = SCEmpleadoEstudio::find($id);
            $empleado->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroyLaboral($id)
    {
        try {
            $empleado = SCEmpleadoLaboral::find($id);
            $empleado->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function findById($id)
    {
        $empleado = SCEmpleado::where('empleado',$id)->first();
        $educacion = SCEmpleadoEstudio::where('empleado',$id)->get();
        $familia = SCEmpleadoFamilia::where('empleado',$id)->get();
        $laboral = SCEmpleadoLaboral::where('empleado',$id)->get();
        if($empleado){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del fondo ya se encuentra registrado',
                'data' => $empleado,
                'educacion' => $educacion,
                'familia' => $familia,
                'laboral' => $laboral
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'no existe'
        ],204);
    }
    //  CAMBIOS A ID
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

    public function dicotomia($valor)
    {
        $tipos = array(
            array("Si",1),
            array("No",2)
        );
        
        $longitud = count($tipos);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tipos[$i][0]) {
                $idtipos = $tipos[$i][1];
            }
        }

        return $idtipos;
    }

    public function tipoDocumento($valor)
    {
        $tipos = array(
            array("CC",1),
            array("Nit",2),
            array("Tipo documento extranjero VAT",3)
        );
        
        $longitud = count($tipos);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tipos[$i][0]) {
                $idtipos = $tipos[$i][1];
            }
        }

        return $idtipos;
    }

    public function sangre($valor)
    {
        $tipos = array(
            array("A",1),
            array("B",2),
            array("AB",3),
            array("O",4)
        );
        
        $longitud = count($tipos);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tipos[$i][0]) {
                $idtipos = $tipos[$i][1];
            }
        }

        return $idtipos;
    }

    public function genero($valor)
    {
        $tipos = array(
            array("Masculino",1),
            array("Femenino",2)
        );
        
        $longitud = count($tipos);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tipos[$i][0]) {
                $idtipos = $tipos[$i][1];
            }
        }

        return $idtipos;
    }

    public function rhs($valor)
    {
        $tipos = array(
            array("Positivo",1),
            array("Negativo",2)
        );
        
        $longitud = count($tipos);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tipos[$i][0]) {
                $idtipos = $tipos[$i][1];
            }
        }

        return $idtipos;
    }

    public function estadoCivil($valor)
    {
        $tipos = array(
            array("Soltero",1),
            array("Casado",2)
        );
        
        $longitud = count($tipos);

        for ($i=0; $i < $longitud; $i++) { 
            if ($valor == $tipos[$i][0]) {
                $idtipos = $tipos[$i][1];
            }
        }

        return $idtipos;
    }

    public function claseCuenta($valor)
    {
        $tipos = array(
            array("Ahorro",1),
            array("Corriente",2)
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
