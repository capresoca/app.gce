<?php

namespace App\Http\Controllers\AtencionUsuario\CentroRegulador;

use App\Http\Requests\AtencionUsuario\CentroRegulador\RefpresentacioneRequest;
use App\Http\Resources\AtencionUsuario\CentroRegulador\RefpresentacioneResource;
use App\Models\CentroRegulador\AuRefbitacora;
use App\Models\CentroRegulador\AuRefpresentacione;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class RefpresentacioneController extends Controller
{
    public function index()
    {
        return RefpresentacioneResource::collection(AuRefpresentacione::with('entidad','referencia','bitacoras')->orderBy('updated_at','desc')->get());
    }

    public function store(RefpresentacioneRequest $request)
    {
//        DB::beginTransaction();
        $au_refpresentacione = AuRefpresentacione::create($request->all());
        $this->bitacora($request, $au_refpresentacione);
//        DB::commit();
        return new RefpresentacioneResource($au_refpresentacione);
    }

    public function show(AuRefpresentacione $au_refpresentacione)
    {
        return new RefpresentacioneResource($au_refpresentacione->load('entidad','referencia'));
    }

    public function update(RefpresentacioneRequest $request, AuRefpresentacione $au_refpresentacione)
    {
        $au_refpresentacione->update($request->all());
        $this->bitacora($request, $au_refpresentacione);
        dd($request);
        return new RefpresentacioneResource($au_refpresentacione);
    }

    public function destroy($id) {}

    public function bitacora ($request, $au_refpresentacione) {
        if ($request->id === '' || $request->id === null) {
            $au_refbitacora = new AuRefbitacora();
            $au_refbitacora->au_referencia_id = $request->au_referencia_id;
            $au_refbitacora->au_refpresentacion_id = $au_refpresentacione->id;
            if ($request->itemdetalle === 'Presentar') {
                $au_refbitacora->fecha = $request->fecha_presentacion;
                $au_refbitacora->au_tipaccion_id = 1;
            } else if ($request->itemdetalle === 'Aceptar') {
                $au_refbitacora->fecha = $request->fecha_aceptacion;
                $au_refbitacora->au_tipaccion_id = 2;
            } else {
                $au_refbitacora->fecha = Carbon::now()->toDateString();
            }
        } else {
            $au_refbitacora = AuRefbitacora::find($request->id);
            $au_refbitacora->au_referencia_id = $request->au_referencia_id;
            $au_refbitacora->au_refpresentacion_id = $au_refpresentacione->id;
            if ($request->itemdetalle === 'Presentar') {
                $au_refbitacora->fecha = $request->fecha_presentacion;
                $au_refbitacora->au_tipaccion_id = 1;
            } else if ($request->itemdetalle === 'Aceptar') {
                $au_refbitacora->fecha = $request->fecha_aceptacion;
                $au_refbitacora->au_tipaccion_id = 2;
            } else {
                $au_refbitacora->fecha = Carbon::now();
            }
        }
        $au_refbitacora->save();
        return new Resource($au_refbitacora);
    }
}
