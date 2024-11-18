<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\CuentasMedicas\CmComplicacionparto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class ComplicacionpartoController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $complicacion_parto = CmComplicacionparto::pimp()
                ->orderBy('nombre','asc')
                ->paginate(Input::get('per_page'));
            return new Resource($complicacion_parto);
        }
        $complicacion_parto = CmComplicacionparto::pimp()
            ->orderBy('nombre','asc')
            ->limit(100)
            ->get();
        return new Resource($complicacion_parto);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required']);
        $complicacion_parto = CmComplicacionparto::create($request->all());
        return new Resource($complicacion_parto);
    }

    public function show($id)
    {
        return new Resource(CmComplicacionparto::where('id',$id)->get());
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required']);
        $complicacion_parto = CmComplicacionparto::find($id);
        $complicacion_parto->update($request->all());
        return new Resource($complicacion_parto);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'DeleteRestrict']);
    }
}
