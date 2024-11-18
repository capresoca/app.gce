<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Requests\Pagos\PgConpagoRequest;
use App\Http\Resources\Pagos\PgConpagoResource;
use App\Models\Pagos\PgConpago;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PgConpagoController extends Controller
{
    public function index()
    {
        return PgConpagoResource::collection(PgConpago::with('niif')->orderBy('updated_at')->get());
    }


    public function store(PgConpagoRequest $request)
    {
        $pg_conpago = PgConpago::create($request->all());
        $pg_conpago->load('niif');

        return new PgConpagoResource($pg_conpago);
    }


    public function show(PgConpago $pg_conpago)
    {
//        $pg_conpago = $pg_conpago->load('niif');
        return new PgConpagoResource($pg_conpago);
    }


    public function update(PgConpagoRequest $request, PgConpago $pg_conpago)
    {
        $pg_conpago->update($request->all());
        $pg_conpago->load('niif');

        return (new PgConpagoResource($pg_conpago))->response()->setStatusCode(202);

    }
}
