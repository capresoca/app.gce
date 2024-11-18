<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Resources\RedServicios\EntidadesResource;
use App\Models\AuditorCuentas\AcAuditore;
use App\Models\CuentasMedicas\CmAuditorConcurrencia;
use App\Models\CuentasMedicas\CmCenso;
use App\Models\CuentasMedicas\CmConcurrencia;
use App\Models\CuentasMedicas\CmConvisita;
use App\Models\CuentasMedicas\CmPacientesHospitalario;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use mysql_xdevapi\Exception;

class PacientesHospitalariosController extends Controller
{

    public function index()
    {
        if(Input::get('per_page') ){
            return Resource::collection(
                CmPacientesHospitalario::with(
                    'diagnostico','afiliado','censo.ips','concurrencia.auditores.usuario')->pimp()
                    ->orderBy('orden')->orderBy('as_afiliado_id','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            CmPacientesHospitalario::with(
                'diagnostico','afiliado','censo.ips','concurrencia.auditores.usuario')
                ->pimp()->limit(1000)->orderBy('orden')->orderBy('as_afiliado_id','desc')->get()
        );
    }

    public function store(Request $request)
    {
        //
    }

    public function show(CmPacientesHospitalario $hospitalario)
    {
        return new Resource($hospitalario->load('diagnostico','afiliado'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function asignarConcurrencia(Request $request)
    {
        if ($this->noAsignacionPorUnoOMasiva($request)){
            throw new \Exception('No es posible asignar nuevos auditores masivamente 
            porque una o varias concurrencias presentan estado Cerrada');
        }

        foreach ($request->pacientes as $paciente){
            $pacienteHospitalario = CmPacientesHospitalario::with('concurrencia')->find($paciente);

            $concurrencia = $this->crearConcurrenciaFromPaciente($pacienteHospitalario);

            $this->asignarAuditores($concurrencia,$request->auditores);

            $pacienteHospitalario->estado = 'Asignado';
            $pacienteHospitalario->cm_concurrencia_id = $concurrencia->id;
            $pacienteHospitalario->save();
        }

        return response(new Resource(['data' => ['ok' => true]]))->setStatusCode('202');
    }

    /**
     * @param $pacienteHospitalario
     * @throws \Exception
     */
    private function crearConcurrenciaFromPaciente($pacienteHospitalario)
    {
        if(!$pacienteHospitalario->as_afiliado_id){
            throw new \Exception('El afiliado no existe');
        }

        $cie10_id = null;

        if($pacienteHospitalario->cod_dx){
            $cie10 = RsCie10::where('codigo',$pacienteHospitalario->cod_dx)
                            ->orWhere('codigo_tres',$pacienteHospitalario->cod_dx)->first();
            if($cie10){

                $cie10_id = $cie10->id;
            }
        }

        if($this->isAsignable($pacienteHospitalario)){
            $concurrencia = CmConcurrencia::where(
                [
                    'as_afiliado_id'    => $pacienteHospitalario->as_afiliado_id,
                    'consecutivo_ips'   => $pacienteHospitalario->consecutivo_entidad,
                    'fecha'             => $pacienteHospitalario->fecha_ingreso,
                    'rs_entidad_id'     => $pacienteHospitalario->censo->ips_id,
                    'rs_cie10_ingreso'  => $cie10_id
                ]
            )->first();

            return $concurrencia;
        }

        $concurrencia = CmConcurrencia::create([
            'as_afiliado_id'    => $pacienteHospitalario->as_afiliado_id,
            'consecutivo_ips'   => $pacienteHospitalario->consecutivo_entidad,
            'fecha'             => $pacienteHospitalario->fecha_ingreso,
            'rs_entidad_id'     => $pacienteHospitalario->censo->ips_id,
            'rs_cie10_ingreso'  => $cie10_id
        ]);

        return $concurrencia;
    }

    private function asignarAuditores($concurrencia, $auditores)
    {
        foreach ($auditores as $auditor){
            $concurrencia->auditores()->attach($auditor,['user_id' => Auth::user()->id] );
        }
    }

    public function entidadesConPacientes()
    {
        $entidades = RsEntidade::whereHas('censos')->get();
        return EntidadesResource::collection($entidades);
    }

    public function eliminarAuditorAsignado ($idAuditor, $idConcurrencia, $pacienteId) {
        try {
            $usuario = AcAuditore::find($idAuditor);

            $cm_visita = CmConvisita::where('gs_usuario_id',$usuario->gs_usuario_id)
                ->where('cm_concurrencia_id',$idConcurrencia)->first();

            if (isset($cm_visita)) {
                throw new \Exception('El auditor no puede eliminarse, 
                presenta registros en la concurrencia del paciente.');
            }

            $cm_concurrencia = CmConcurrencia::where('estado','=','Cerrada')->whereId($idConcurrencia)->first();

            if (isset($cm_concurrencia)) {
                throw new \Exception('La concurrencia se encuentra cerrada, 
                no es posible eliminar el auditor.');
            }

            $cm_auditor_concurrencia = CmAuditorConcurrencia::where('auditor_id',$idAuditor)
                ->where('concurrencia_id',$idConcurrencia)->first();
            $cm_auditor_concurrencia->delete();
            $this->cambioDeEstado($idConcurrencia, $pacienteId);

            return response(new Resource(['data' => ['ok' => true]]))->setStatusCode('200');

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 422);
        }

    }

    public function noAsignacionPorUnoOMasiva($request)
    {
        $pacientes=[];

        foreach ($request->pacientes as $paciente) {
            $pacienteHospitalario = CmPacientesHospitalario::with('concurrencia')->find($paciente);
            if (($pacienteHospitalario->concurrencia !== null)
                && ($pacienteHospitalario->concurrencia['estado'] === 'Cerrada')) {
                array_push($pacientes, $paciente);
            }
        }

        return count($pacientes) > 0;
    }

    private function cambioDeEstado ($idConcurrencia, $paciente) {
        $concurrenciaAsignada = CmAuditorConcurrencia::where('concurrencia_id',$idConcurrencia)->first();
        if (!isset($concurrenciaAsignada)) {
            $pacienteHospitalario = CmPacientesHospitalario::find($paciente);
            $pacienteHospitalario->estado = 'Sin Asignar';
            $pacienteHospitalario->save();
        }
    }

    /**
     * @param $pacienteHospitalario
     * @return bool
     */
    private function isAsignable($pacienteHospitalario): bool
    {
        return $pacienteHospitalario->estado != 'Sin Asignar'
            || ($pacienteHospitalario->concurrencia !== null
                && $pacienteHospitalario->concurrencia['estado'] === 'Abierta');
    }
}
