<?php

namespace App\Http\Controllers\Niif;

use App\Http\Requests\Niif\CencostoRequest;
use App\Http\Resources\Niif\CencostosResource;
use App\Models\Niif\NfCencosto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CencostoController extends Controller
{


    public function index()
    {
        return CencostosResource::collection(NfCencosto::orderBy('updated_at')->get());
    }


    public function store(CencostoRequest $request)
    {
        $cencosto = NfCencosto::create($request->all());

        return new CencostosResource($cencosto);
    }


    public function show(NfCencosto $cencosto)
    {
        return new CencostosResource($cencosto);
    }


    public function update(CencostoRequest $request, NfCencosto $cencosto)
    {
        $cencosto->update($request->all());

        return new CencostosResource($cencosto);

    }


}
