<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\CuentasMedicas\CmComplicacionneonato;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class ComplicacionneonatoController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $complicacion_neonato = CmComplicacionneonato::pimp()
                ->orderBy('nombre','asc')
                ->paginate(Input::get('per_page'));
            return new Resource($complicacion_neonato);
        }
        $complicacion_neonato = CmComplicacionneonato::pimp()
            ->orderBy('nombre','asc')
            ->limit(100)
            ->get();
        return new Resource($complicacion_neonato);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required']);
        $complicacion_neonato = CmComplicacionneonato::create($request->all());
        return new Resource($complicacion_neonato);
    }

    public function show($id)
    {
        return new Resource(CmComplicacionneonato::where('id',$id)->get());
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required']);
        $complicacion_neonato = CmComplicacionneonato::find($id);
        $complicacion_neonato->update($request->all());
        return new Resource($complicacion_neonato);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'DeleteRestrict']);
    }
}
