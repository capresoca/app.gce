<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Requests\Pagos\PgProveedoreRequest;
use App\Http\Resources\Pagos\PgProveedoreResource;
use App\Models\Pagos\PgProveedore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class PgProveedoreController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return PgProveedoreResource::collection(
                PgProveedore::with('niif','tercero')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return PgProveedoreResource::collection(PgProveedore::with('niif','tercero')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(PgProveedoreRequest $request)
    {
        $pg_proveedore = PgProveedore::create($request->all());
        $pg_proveedore->load(['niif','tercero']);

        return new PgProveedoreResource($pg_proveedore);
    }

    public function show(PgProveedore $pg_proveedore)
    {
        return new PgProveedoreResource($pg_proveedore);
    }

    public function update(PgProveedoreRequest $request, PgProveedore $pg_proveedore)
    {
        try {
            $pg_proveedore->update($request->all());
            $pg_proveedore->load(['niif','tercero']);
            return (new PgProveedoreResource($pg_proveedore))->response()->setStatusCode(202);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception
            ], 500);
        }
    }

}
