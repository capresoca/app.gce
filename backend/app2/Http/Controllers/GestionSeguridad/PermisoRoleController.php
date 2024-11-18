<?php

namespace App\Http\Controllers\GestionSeguridad;

use App\Http\Requests\GestionSeguridad\PermisoRoleRequest;
use App\Http\Resources\GestionSeguridad\PermisoRoleResource;
use App\Seguridad\GsPermisoRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermisoRoleController extends Controller
{

    public function index()
    {
        return PermisoRoleResource::collection(GsPermisoRole::with('GsComponente')->orderBy('updated_at','desc')->get());
    }

    public function store(PermisoRoleRequest $request)
    {
        try {
            $gsPermisoRole = GsPermisoRole::create($request->all());
//            $gsPermisoRole->load('GsComponente','GsRoles');
            return response()->json([
                'message' => 'Asignó permisos correctamente.',
                'permisoRole' => new PermisoRoleResource($gsPermisoRole)
            ], 201);
        } catch (\Exception $e){
            return response()->json([
                'errors' => $e,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(GsPermisoRole $gsPermisoRole)
    {
//        $gsPermisoRole->load('GsComponente','GsRoles');
        return new PermisoRoleResource($gsPermisoRole);
    }

    public function update(PermisoRoleRequest $request, $id)
    {
        try {
            $gsPermisoRole = GsPermisoRole::find($id);
            $gsPermisoRole->update($request->all());
//            $gsPermisoRole->load('GsComponente','GsRoles');
            return response()->json([
                'message' => 'Asignó permisos correctamente.',
                'permisoRole' => new PermisoRoleResource($gsPermisoRole)
            ], 202);

        }catch (\Exception $e) {
            return response()->json([
                'errors' => $e,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
