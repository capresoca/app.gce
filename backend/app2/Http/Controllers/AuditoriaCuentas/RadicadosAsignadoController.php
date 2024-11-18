<?php

namespace App\Http\Controllers\AuditoriaCuentas;

use App\Http\Requests\AuditoriaCuentas\RadicadosAsignadoRequest;
use App\Http\Resources\AuditoriaCuentas\RadicadosAsignadoResource;
use App\Models\AuditorCuentas\AcAuditore;
use App\Models\AuditorCuentas\AcRadicadosAsignado;
use App\Models\CuentasMedicas\CmFactura;
use App\Models\CuentasMedicas\CmManglosa;
use App\Models\CuentasMedicas\CmRadicado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class RadicadosAsignadoController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return RadicadosAsignadoResource::collection(
                CmRadicado::with('entidad.tercero',
                    'contrato', 'facturas.servicios', 'auditoresAsignados.auditor.usuario'
                )->whereHas('auditoresAsignados')
                    ->pimp()
                    ->orderBy('consecutivo', 'desc')
                    ->paginate(
                        Input::get('per_page')
                    )
            );
        }
        return RadicadosAsignadoResource::collection(
            CmRadicado::with(
                'entidad.tercero',
                'contrato',
                'facturas.servicios',
                'auditoresAsignados.auditor.usuario'
            )->whereHas('auditoresAsignados')
                ->pimp()
                ->orderBy('consecutivo', 'desc'
                )->get()
        );
    }

    public function indexAutitorMedico()
    {
        $auditor = AcAuditore::where('gs_usuario_id',Auth::user()->id)->first();

        $referencias = CmFactura::with('auditores','radicado')
            ->whereNotIn('estado',['Avalada2','Conciliada'])
            ->whereHas('auditores',function ($query) use ($auditor){
                $query->where('ac_auditores.id',$auditor['id']);
            })->pimp()->orderBy('consecutivo', 'desc')->paginate(Input::get('per_page'));

        return Resource::collection($referencias);

    }

    public function store(RadicadosAsignadoRequest $request) {}

    public function show(AcRadicadosAsignado $radicados_asignado)
    {
        return new Resource($radicados_asignado->load('auditor.usuario','radicado.facturas','radicado.contrato','radicado.entidad'));
    }

    public function update(Request $request, $id){}

    public function destroy($id){}

    public function getGlosasComplementos () {
        $complementos = collect();
        $complementos->put('glosas', CmManglosa::all());
        return response()->json(
            [
                'data' => $complementos
            ]
        ,200);
    }
}
