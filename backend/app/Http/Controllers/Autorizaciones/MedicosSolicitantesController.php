<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Http\Requests\Autorizaciones\MedicoRequest;
use App\Http\Resources\Aseguramiento\AsAfiliadoResource;
use App\Http\Resources\Aseguramiento\AsAfiliadosResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Autorizaciones\AuMedicosSolicitante;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class MedicosSolicitantesController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return Resource::collection(
                AuMedicosSolicitante::with('especialidad')->pimp()
                    ->orderBy('id','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            AuMedicosSolicitante::with('especialidad')->pimp()->limit(100)->orderBy('updated_at','desc')->get()
        );
    }

    public function store(MedicoRequest $request)
    {
        $medico = AuMedicosSolicitante::create($request->all());
        $medico->load('especialidad');
        return new Resource($medico);
    }

    public function update(MedicoRequest $request, AuMedicosSolicitante $medico)
    {
        $medico->update($request->all());
        $medico->load('especialidad');

        return new Resource($medico);
    }
}
