<?php

namespace App\Http\Controllers\RedServicios;

use App\Models\RedServicios\RsCupscontratados;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class CupContratadoController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return  Resource::collection(
                RsCupscontratados::pimp()
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            RsCupscontratados::pimp()->with('cup')->get()
        );
    }
}
