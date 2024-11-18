<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Requests\RedServicios\CupRequest;
use App\Http\Resources\RedServicios\CumResource;
use App\Http\Resources\RedServicios\CupResource;
use App\Models\RedServicios\RsCum;
use App\Models\RedServicios\RsCups;
use App\Traits\EnumsTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CupController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return CupResource::collection(
                RsCups::with('cupscategoria', 'soat' , 'maniss')->pimp()
                    ->paginate(Input::get('per_page'))
            );
        }

        if(Input::get('tree')){
            return CupResource::collection(
                RsCups::with('cupscategoria')->pimp()
                    ->paginate(Input::get('per_page'))
            );
        }
        return CumResource::collection(RsCups::with('cupscategoria', 'soat' , 'maniss')->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(CupRequest $request)
    {
        $cup = RsCups::create($request->all());
        $cup->load('cupscategoria', 'soat' , 'maniss');
        return new CupResource($cup);
    }

    public function show(RsCups $cup)
    {
        return new CupResource($cup->load('cupscategoria', 'soat' , 'maniss'));
    }

    public function update(CupRequest $request, RsCups $cup)
    {
        $cup->update($request->all());
        $cup->load('cupscategoria', 'soat' , 'maniss');
        return (new CupResource($cup))->response()->setStatusCode(202);
    }

    public function complementos()
    {
        return response()->json(
            [
                'generos' => EnumsTrait::getEnumValues('rs_cupss','genero'),
                'ambitos' => EnumsTrait::getEnumValues('rs_cupss', 'ambito'),
                'estancias' => EnumsTrait::getEnumValues('rs_cupss','estancia'),
                'coberturas' => EnumsTrait::getEnumValues('rs_cupss','cobertura'),
                'duplicados' => EnumsTrait::getEnumValues('rs_cupss','duplicado'),
                'vidas' => EnumsTrait::getEnumValues('rs_cupss', 'vida'),
            ]
        );
    }

}
