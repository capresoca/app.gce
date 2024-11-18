<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Requests\Aseguramiento\AfpRequest;
use App\Http\Resources\Aseguramiento\AfpResource;
use App\Models\Aseguramiento\AsAfp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AfpController extends Controller
{

    public function index()
    {
        return AfpResource::collection(AsAfp::orderBy('updated_at', 'desc')->get());
    }


    public function store(AfpRequest $request)
    {
        $as_afp = AsAfp::create($request->all());
        return new AfpResource($as_afp);
    }


    public function show(AsAfp $as_afp)
    {
        return new AfpResource($as_afp);
    }


    public function update(AfpRequest $request, AsAfp $as_afp)
    {
        $as_afp->update($request->all());

        return new AfpResource($as_afp);

    }
}
