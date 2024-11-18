<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Resources\Aseguramiento\AsAltocostosResource;
use App\Models\Aseguramiento\AsAfiliadoAltocosto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AfiliadoAltocostoController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return AsAltocostosResource::collection(
                AsAfiliadoAltocosto::with(AsAfiliadoAltocosto::allRelations())->pimp()
                    ->orderBy('id','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return AsAltocostosResource::collection(AsAfiliadoAltocosto::with(AsAfiliadoAltocosto::allRelations())
            ->pimp()->limit(100)->orderBy('id','desc')->get());
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
