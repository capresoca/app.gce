<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Requests\Aseguramiento\EpsRequest;
use App\Http\Resources\Aseguramiento\EpsResource;
use App\Models\Aseguramiento\AsEps;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EpsController extends Controller
{
    public function index()
    {
        return EpsResource::collection(AsEps::orderBy('updated_at', 'desc')->get());
    }


    public function store(EpsRequest $request)
    {
        $asEps = AsEps::create($request->all());

        return new EpsResource($asEps);
    }


    public function show(AsEps $asEps)
    {
        return new EpsResource($asEps);
    }


    public function update(EpsRequest $request, $id)
    {
        $eps = AsEps::find($id);
        $eps->update($request->all());

        return new EpsResource($eps);

    }
}
