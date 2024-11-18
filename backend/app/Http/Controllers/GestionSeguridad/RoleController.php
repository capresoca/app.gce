<?php

namespace App\Http\Controllers\GestionSeguridad;

use App\Http\Requests\GestionSeguridad\GsRoleRequest;
use App\Http\Resources\GestionSeguridad\GsRoleResource;
use App\Seguridad\GsRole;
use App\Seguridad\GsModulo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    public function index()
    {
        $modulos = GsModulo::with('componentes')->orderBy('updated_at', 'desc')->get();
        $roles =GsRole::orderBy('updated_at', 'desc')->get();
        $infoRol = collect();
        $infoRol->put('modulos', $modulos);
        $infoRol->put('roles', $roles);
        return new GsRoleResource($infoRol);
    }


    public function store(GsRoleRequest $request)
    {
        $gs_role = GsRole::create($request->all());

        return new GsRoleResource($gs_role);
    }


    public function show(GsRole $gs_role)
    {
        $gs_role->load('permisos');
        return new GsRoleResource($gs_role);
    }


    public function update(GsRoleRequest $request, $id)
    {
        $gs_role = GsRole::find($id);
        $gs_role->update($request->all());

        return new GsRoleResource($gs_role);

    }
}
