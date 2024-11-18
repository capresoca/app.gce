<?php

namespace App\Http\Controllers;

use App\GsComponente;
use App\GsPermisoRole;
use App\Http\Requests\GestionSeguridad\GsUsuarioChangePasswordRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    public function __construct()
    {
//        $this->middleware('auth:api', ['except' => ['login','refresh']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login()
    {
//        $credentials = request(['email', 'password']);
        //$credentials['tipo'] = 'eps';
        $credentials = $this->validate(request(), [
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        if (!$credentials) return response()->json(['error' => 'Credenciales incorrectas'], 422);

        $credentials['state'] = 'Activo';
        $credentials['tipo'] = 'Funcionario';

        $token = auth()->attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'Credenciales incorrectas'], 422);
        } else {
            return $this->respondWithToken($token);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $rolUser = Auth::user()->roles->pluck('id');
            $permisos = GsPermisoRole::where('ver', '1')->whereIn('gs_role_id', $rolUser)->with('GsComponente')->get();

            $data = [];

            foreach ($permisos as $key => $permiso) {

                //$componente_exist = $data->where('id_componente',$permiso->GsComponente->id);

                $componente_exist = array_first($data, function ($value, $key) use ($permiso) {
                    return $value['id_componente'] === $permiso->GsComponente->id;
                });


//                if($componente_exist){
//                    $data[$componente_exist['key']]['permisos'] = [
//                        'ver' => $permiso->ver ? $componente_exist['permisos']['ver'] : $componente_exist['permisos']['ver'],
//                        'agregar' => $permiso->agregar ? $componente_exist['permisos']['agregar'] : $componente_exist['permisos']['agregar'],
//                        'confirmar' => $permiso->confirmar ? $componente_exist['permisos']['confirmar'] : $componente_exist['permisos']['confirmar'],
//                        'anular' => $permiso->anular ? $componente_exist['permisos']['anular'] : $componente_exist['permisos']['anular'],
//                        'imprimir' => $permiso->imprimir ? $componente_exist['permisos']['imprimir'] : $componente_exist['permisos']['imprimir']
//                    ];
//                    continue;
//
//                }


                array_push($data, [
                    'key' => $key,
                    'id_componente' => $permiso->GsComponente->id,
                    'titulo' => $permiso->GsComponente->nombre,
                    'ruta' => $permiso->GsComponente->ruta_router,
                    'componente' => $permiso->GsComponente->ruta_componente,
                    'permisos' => [
                        'ver' => $permiso->ver,
                        'agregar' => $permiso->agregar,
                        'confirmar' => $permiso->confirmar,
                        'anular' => $permiso->anular,
                        'imprimir' => $permiso->imprimir,
                    ],
                    'icono' => $permiso->GsComponente->icono,
                    'descripcion' => $permiso->GsComponente->descripcion,
                    'color' => $permiso->GsComponente->GsModulo->color,
                    'modulo' => $permiso->GsComponente->GsModulo->nombre,
                    'ruta_registro' => $permiso->GsComponente->ruta_router_registro,
                    'titulo_registro' => $permiso->GsComponente->titulo_registro,
                    'view_menu' => $permiso->GsComponente->view_menu,
                    'favorito' => $permiso->GsComponente->esFavorito(Auth::user()->id)
                ]);
            }


            return response()->json([
                'user' => auth()->user(),
                'permisos' => $data,
                'db' => env("DB_DATABASE", "")
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh());
        } catch (TokenExpiredException $e) {
            return response($e->getMessage(), 403);
        } catch (JWTException $e) {
            return response($e->getMessage(), 403);
        }

    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'user' => auth()->user()
        ]);
    }


    public function changePassword(GsUsuarioChangePasswordRequest $request)
    {
        if (Hash::check($request->current_password, Auth::user()->password)) {
            $user = Auth::user();
            $user->password = bcrypt($request->password);
            $user->save();
            return response('Contraseña actualizada correctamente', 200);
        } else {
            return response('Credenciales no coinciden', 422);
        }
    }


}
