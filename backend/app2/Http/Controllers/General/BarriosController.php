<?php

namespace App\Http\Controllers\General;

use App\Models\General\GnBarrio;
use App\Models\General\GnMunicipio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class BarriosController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $barrios = GnBarrio::with('municipio')->pimp()->orderBy('nombre', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($barrios);
        }
        return Resource::collection(GnBarrio::pimp()->orderBy('nombre', 'desc')->get());
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'gn_municipio_id'   => 'required|exists:gn_municipios,id',
            'nombre'            => 'required'
        ]);

        $barrio = new GnBarrio();
        $barrio->fill($request->all());

        $municipio = GnMunicipio::find($request->gn_municipio_id);
        $consecutivo = GnBarrio::where('gn_municipio_id', $request->gn_municipio_id)->max('codigo');
        $barrio->codigo = $municipio->codigo. substr($consecutivo,-2) + 1;
        $barrio->save();

        return new Resource($barrio);
    }

    public function destroy(GnBarrio $barrio)
    {
        try {
            $barrio->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
