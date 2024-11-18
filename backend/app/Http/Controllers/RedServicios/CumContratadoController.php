<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Resources\RedServicios\CumResource;
use App\Models\RedServicios\RsCumcontratados;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class CumContratadoController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return  Resource::collection(
                RsCumcontratados::pimp()
                    ->paginate(Input::get('per_page'))
            );
        }


        return Resource::collection(
            RsCumcontratados::pimp()->get()
        );
    }
}
