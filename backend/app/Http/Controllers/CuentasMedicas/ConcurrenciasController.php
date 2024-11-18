<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConcurrenciaRequest;
use App\Http\Resources\AuditoriaConcurrente\ConcurrenciaResource;
use App\Models\AuditorCuentas\AcAuditore;
use App\Models\CuentasMedicas\CmAuditorConcurrencia;
use App\Models\CuentasMedicas\CmConcurrencia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ConcurrenciasController extends Controller
{
    public function index(){

        if(Input::get('per_page')){
            return ConcurrenciaResource::collection(CmConcurrencia::with(CmConcurrencia::allRelations())
                ->pimp()->orderBy('consecutivo', 'desc')->paginate(Input::get('per_page')));
        }
        return ConcurrenciaResource::collection(
                CmConcurrencia::with(CmConcurrencia::allRelations())
                ->pimp()
                ->orderBy('consecutivo', 'desc')->get());
    }

    public function indexCocurrente()
    {
        $auditor = AcAuditore::where('gs_usuario_id',Auth::user()->id)->first();

        $referencias = CmConcurrencia::where('estado','Abierta')
        ->whereHas('auditores',function ($query) use ($auditor){
            $query->where('ac_auditores.id',$auditor['id']);
        })->with(CmConcurrencia::allRelations())
            ->pimp()->orderBy('consecutivo', 'desc')->paginate(Input::get('per_page'));

        return ConcurrenciaResource::collection($referencias);

    }

    public function show($id){
        $concurrencia = CmConcurrencia::find($id);
        $noTieneAuditor = $concurrencia->auditores;
        if($noTieneAuditor->isEmpty()) {
            $auditor = AcAuditore::where('gs_usuario_id',$concurrencia->gs_usuario_id)->first();
            if($auditor) $concurrencia->auditores()->attach($auditor->id,['user_id' => Auth::user()->id] );
        };
        return new Resource($concurrencia->load(CmConcurrencia::allRelations()));
    }

    public function store(ConcurrenciaRequest $request){
        $concurrencia = CmConcurrencia::create($request->all());
        $auditor = AcAuditore::where('gs_usuario_id',Auth::user()->id)->first();
        $concurrencia->auditores()->attach($auditor->id,['user_id' => Auth::user()->id] );
        return new Resource($concurrencia->load(CmConcurrencia::allRelations()));
    }

    public function update(ConcurrenciaRequest $request, $id){
        $concurrencia = CmConcurrencia::find($id);
        $concurrencia->update($request->except('consecutivo', 'estado'));
        return new Resource($concurrencia->load(CmConcurrencia::allRelations()));
        //return ConcurrenciaResource::collection($concurrencia->load(CmConcurrencia::allRelations()));
    }
}
