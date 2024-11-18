<?php

namespace App\Http\Controllers\RedServicios;

use App\Models\RerServicios\RsTipcapinstalada;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class TipcapinstaladaController extends Controller
{
    public function index()
    {
        return Resource::collection(RsTipcapinstalada::get());
    }

    public function show(RsTipcapinstalada $tipcapinstalada)
    {
        return new Resource($tipcapinstalada);
    }

    public function store(Request $request)
    {

        $this->validate($request,$this->validationRules());

        return (new Resource(RsTipcapinstalada::create($request->all())))
                ->response()
                ->setStatusCode(201);

    }


    public function update(RsTipcapinstalada $tipcapinstalada,Request $request)
    {
        $this->validate($request,$this->validationRules());

        $tipcapinstalada->update($request->all());

        return (new Resource($tipcapinstalada))
            ->response()
            ->setStatusCode(202);
    }

    private function validationRules()
    {
        return [
            'tipo' => 'required|string|max:500',
            'descripcion' => 'required|string|max:500',
        ];
    }
}
