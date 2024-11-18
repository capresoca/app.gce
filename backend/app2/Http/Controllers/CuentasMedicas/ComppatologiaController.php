<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\CuentasMedicas\CmComppatologia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class ComppatologiaController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')){
            $complicacion_patologia = CmComppatologia::pimp()
                ->orderBy('nombre','asc')
                ->paginate(Input::get('per_page'));
            return new Resource($complicacion_patologia);
        }
        $complicacion_patologia = CmComppatologia::pimp()
            ->orderBy('nombre','asc')
            ->limit(100)
            ->get();
        return new Resource($complicacion_patologia);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required']);
        $complicacion_patologia = CmComppatologia::create($request->all());
        return new Resource($complicacion_patologia);
    }

    public function show($id)
    {
        return new Resource(CmComppatologia::where('id',$id)->get());
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required']);
        $complicacion_patologia = CmComppatologia::find($id);
        $complicacion_patologia->update($request->all());
        return new Resource($complicacion_patologia);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'DeleteRestrict']);
    }
}
