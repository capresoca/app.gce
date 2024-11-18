<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Requests\Pagos\PgUniapoyoRequest;
use App\Http\Resources\Pagos\PgUniapoyoResource;
use App\Models\Pagos\PgUniapoyo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PgUniapoyoController extends Controller
{
    public function index()
    {
        return PgUniapoyoResource::collection(PgUniapoyo::with('cencosto')->orderBy('updated_at')->get());
    }

    public function store(PgUniapoyoRequest $request)
    {
        $pg_uniapoyo = PgUniapoyo::create($request->all());
        $pg_uniapoyo->load('cencosto');

        return new PgUniapoyoResource($pg_uniapoyo);
    }

    public function show(PgUniapoyo $pg_uniapoyo)
    {
        return new PgUniapoyoResource($pg_uniapoyo);
    }

    public function update(PgUniapoyoRequest $request, PgUniapoyo $pg_uniapoyo)
    {
        $pg_uniapoyo->update($request->all());
        $pg_uniapoyo->load('cencosto');

        return (new PgUniapoyoResource($pg_uniapoyo))->response()->setStatusCode(202);

    }

//    public function destroy($id)
//    {
//        //
//    }
}
