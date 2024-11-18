<?php

namespace App\Http\Middleware;

use App\GsPermisoRole;
use Closure;
use Illuminate\Support\Facades\Auth;

class Permite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permiso, $componente_id)
    {

        $user = Auth::user();
        $roles_permitidos = GsPermisoRole::where('gs_componente_id', $componente_id)
                                ->where($permiso, 1)->get()->pluck('gs_role_id');

        if($user && $user->hasAnyRole($roles_permitidos)){
            return $next($request);
        };

        return response()->json(['message'=>'No esta autorizado para esta acción'],423);

    }

}
