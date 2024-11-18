<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Requests\Aseguramiento\ArlRequest;
use App\Http\Resources\Aseguramiento\ArlResource;
use App\Models\Aseguramiento\AsArl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArlController extends Controller
{
    public function index()
    {
        return ArlResource::collection(AsArl::orderBy('updated_at', 'desc')->get());
    }


    public function store(ArlRequest $request)
    {
        $as_arl = AsArl::create($request->all());

        return new ArlResource($as_arl);
    }


    public function show(AsArl $as_arl)
    {
        return new ArlResource($as_arl);
    }


    public function update(ArlRequest $request, AsArl $as_arl)
    {
        $as_arl->update($request->all());

        return new ArlResource($as_arl);

    }
}
