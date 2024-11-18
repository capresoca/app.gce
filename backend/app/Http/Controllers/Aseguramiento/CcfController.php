<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Requests\Aseguramiento\CcfRequest;
use App\Http\Resources\Aseguramiento\CcfResource;
use App\Models\Aseguramiento\AsCcf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CcfController extends Controller
{
    public function index()
    {
        return CcfResource::collection(AsCcf::orderBy('updated_at', 'desc')->get());
    }

    public function store(CcfRequest $request)
    {
        $as_ccf = AsCcf::create($request->all());

        return new CcfResource($as_ccf);
    }


    public function show(AsCcf $as_ccf)
    {
        return new CcfResource($as_ccf);
    }


    public function update(CcfRequest $request, AsCcf $as_ccf)
    {
        $as_ccf->update($request->all());

        return new CcfResource($as_ccf);

    }
}
