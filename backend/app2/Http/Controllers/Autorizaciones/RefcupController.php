<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Models\Autorizaciones\Refcup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class RefcupController extends Controller
{
    public function index () {
        if(Input::get('per_page')){
            return Resource::collection(
                Refcup::pimp()
                    ->where('ind','=','1')
                    ->orderBy('codigo')
                    ->paginate(Input::get('per_page'))
            );
        }
        return Resource::collection(
            Refcup::pimp()
                ->where('ind','=','1')
                ->orderBy('codigo')
                ->get());
    }

}
