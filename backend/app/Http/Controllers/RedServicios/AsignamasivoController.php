<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Controllers\Controller;
use App\Http\Resources\RedServicios\AsignamasivoResource;
use App\Http\Resources\RedServicios\AsignamasivosResource;
use App\Models\RedServicios\RsAsignamasivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AsignamasivoController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return AsignamasivosResource::collection(
                RsAsignamasivo::select('rs_asignamasivos.*',
                        'gs_usuarios.name',
                        'gs_usuarios.email')
                    ->leftJoin('gs_usuarios','gs_usuarios.id','rs_asignamasivos.gs_usuario_id')
                ->orderBy('created_at','desc')
                ->paginate(Input::get('per_page')));
        }
        return AsignamasivosResource::collection(RsAsignamasivo::select('rs_asignamasivos.*',
            'gs_usuarios.name',
            'gs_usuarios.email')
            ->leftJoin('gs_usuarios','gs_usuarios.id','rs_asignamasivos.gs_usuario_id')
            ->limit(1000)->get());
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return AsignamasivoResource::collection(
            RsAsignamasivo::where('id',$id)->get()
        )->first();
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
