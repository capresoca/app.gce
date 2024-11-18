<?php

namespace App\Http\Controllers\GestionSeguridad;

use App\Http\Resources\GestionSeguridad\AuditResource;
use App\Models\Seguridad\Audit;
use App\Models\Seguridad\GsModelo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AuditsController extends Controller
{
    //
    public function index(){

        if(Input::get('per_page')){
            if(Input::get('modelo')){
                $modelos = GsModelo::find(Input::get('modelo'))->children()->pluck('id');
                $modelos->push((int)Input::get('modelo'));
                //dd(GsModelo::find(Input::get('modelo'))->parent);
                $modelos ->push((int)GsModelo::find(Input::get('modelo'))->parent->id);
                //$modelos = array_merge($hijos, $padre);

                //dd($modelos);
                return AuditResource::collection(
                    Audit::whereHas('modelo', function($query) use ($modelos){
                        $query->whereIn('id',$modelos);
                    })->pimp()
                        ->orderBy('updated_at','desc')
                        ->paginate(Input::get('per_page'))
                );
            }
            return AuditResource::collection(
                Audit::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return AuditResource::collection(Audit::pimp()->limit(1000)->orderBy('updated_at','desc')->get());

    }
}
