<?php

namespace App\Http\Controllers\RedServicios;

use App\Models\RedServicios\RsCupscontratados;
use App\Models\RedServicios\RsOtroscontratado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class OtrosContratatadoController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return  Resource::collection(
                RsOtroscontratado::pimp()
                    ->paginate(Input::get('per_page'))
            );
        }


        return Resource::collection(
            RsOtroscontratado::pimp()->get()
        );
    }
}
