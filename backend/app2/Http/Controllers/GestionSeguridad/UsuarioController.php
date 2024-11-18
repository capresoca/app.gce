<?php

namespace App\Http\Controllers\GestionSeguridad;

use App\Http\Requests\GestionSeguridad\GsUsuarioRequest;
use App\Http\Resources\GestionSeguridad\GsUsuarioResource;
use App\Models\GestionSeguridad\GsUsuario;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UsuarioController extends Controller
{
    public function index(){

        if(Input::get('per_page')){
            return GsUsuarioResource::collection(
                User::with('entidad')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return GsUsuarioResource::collection(User::with('entidad')->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(GsUsuarioRequest $request)
    {
        $usuario = new User();
        $usuario->fill($request->except('password'));
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        if ($request->tipo !== "Entidad") {
            $usuario->roles()->attach($this->roleID($request->roles));
        }
        $usuario->load('roles','entidad');
        return new GsUsuarioResource($usuario);
    }

    public function update(GsUsuarioRequest $request, $id)
    {
        DB::beginTransaction();
        $usuario = User::find($id);
        $usuario->fill($request->except('password'));
        if($request->password)
            $usuario->password = Hash::make($request->password);

        $usuario->save();
        if ($request->tipo !== "Entidad") {
            $usuario->roles()->detach();
            $usuario->roles()->attach($this->roleID($request->roles));
        }
        $usuario->load('roles','entidad');
        DB::commit();
        return new GsUsuarioResource($usuario);
    }

    public function roleID ($requests) {
        $roles = [];
        foreach ($requests as $res) {
            array_push($roles ,$res['id']);
        }
        return $roles;
    }

    public function show(User $usuario)
    {
        return new GsUsuarioResource($usuario->load('roles','entidad'));
    }

    public function findByEmail($email){
        $usuario = User::where('email',$email)->with('roles')->first();
        if($usuario)
            return response()->json([
                'exists' => true,
                'data' => new GsUsuarioResource($usuario)
            ]);

        return response()->json([
            'exists' => false,
            'message' => 'No existe un usuario con este email'
        ]);
    }

    public function findByIdentification($identification){
        $usuario = User::whereIdentification($identification)->with('roles')->first();
        if($usuario)
            return response()->json([
                'exists' => true,
                'data' => new GsUsuarioResource($usuario)
            ]);

        return response()->json([
            'exists' => false,
            'message' => 'No existe un usuario con esta identificación'
        ]);
    }


}
