<?php

namespace App\Http\Controllers\Presupuesto;

use App\Models\Presupuesto\PrEstructura;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class EstructuraController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return Resource::collection(
                PrEstructura::pimp()
                    ->orderBy('updated_at','desc')
                    ->with('conceptos')
                    ->paginate(Input::get('per_page'))
            );
        }
        return Resource::collection(PrEstructura::pimp()->orderBy('updated_at', 'desc')->get());

    }

    public function show(PrEstructura $estructura)
    {
        $estructura->load('conceptos');
        return new Resource($estructura);
    }
}
