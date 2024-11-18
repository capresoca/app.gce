<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Requests\ContratacionEstatal\NatjuridicaRequest;
use App\Http\Resources\ContratacionEstatal\NatjuridicaResource;
use App\Models\ContratacionEstatal\CeNatjuridica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NatjuridicaController extends Controller
{
    public function index()
    {
        return NatjuridicaResource::collection(CeNatjuridica::orderBy('updated_at', 'desc')->get());
    }


    public function store(NatjuridicaRequest $request)
    {
        $ce_natjuridica = CeNatjuridica::create($request->all());

        return new NatjuridicaResource($ce_natjuridica);
    }


    public function show(CeNatjuridica $ce_natjuridica)
    {
        return new NatjuridicaResource($ce_natjuridica);
    }


    public function update(NatjuridicaRequest $request, CeNatjuridica $ce_natjuridica)
    {
        $ce_natjuridica->update($request->all());

        return new NatjuridicaResource($ce_natjuridica);

    }
}
