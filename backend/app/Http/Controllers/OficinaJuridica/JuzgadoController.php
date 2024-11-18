<?php

namespace App\Http\Controllers\OficinaJuridica;

use App\Http\Requests\OficinaJuridica\JuzgadoRequest;
use App\Http\Resources\OficinaJuridica\JuzgadoResource;
use App\Models\OficinaJuridica\OjJuzgado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class JuzgadoController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return JuzgadoResource::collection(
                OjJuzgado::with('municipio')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return JuzgadoResource::collection(OjJuzgado::with('municipio')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function store(JuzgadoRequest $request)
    {
        $oj_juzgado = OjJuzgado::create($request->all());
        $oj_juzgado->load('municipio');
        return new JuzgadoResource($oj_juzgado);
    }

    public function show(OjJuzgado $oj_juzgado)
    {
        return new JuzgadoResource($oj_juzgado->load('municipio'));
    }

    public function update(JuzgadoRequest $request, OjJuzgado $oj_juzgado)
    {
        $oj_juzgado->update($request->all());
        $oj_juzgado->load('municipio');

        return (new JuzgadoResource($oj_juzgado))->response()->setStatusCode(202);

    }
}
