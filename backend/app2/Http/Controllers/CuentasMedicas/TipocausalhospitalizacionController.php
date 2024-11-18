<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\CuentasMedicas\CmTipocausalhospitalizacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class TipocausalhospitalizacionController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $causal_hospitalizacion = CmTipocausalhospitalizacion::pimp()
                ->orderBy('nombre','asc')
                ->paginate(Input::get('per_page'));
            return new Resource($causal_hospitalizacion);
        }
        $causal_hospitalizacion = CmTipocausalhospitalizacion::pimp()
            ->orderBy('nombre','asc')
            ->limit(100)
            ->get();
        return new Resource($causal_hospitalizacion);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required']);
        $causal_hospitalizacion = CmTipocausalhospitalizacion::create($request->all());
        return new Resource($causal_hospitalizacion);
    }

    public function show($id)
    {
        return new Resource(CmTipocausalhospitalizacion::where('id',$id)->get());
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required']);
        $causal_hospitalizacion = CmTipocausalhospitalizacion::find($id);
        $causal_hospitalizacion->update($request->all());
        return new Resource($causal_hospitalizacion);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'DeleteRestrict']);
    }
}
