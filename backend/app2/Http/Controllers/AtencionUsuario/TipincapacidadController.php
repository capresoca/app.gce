<?php

namespace App\Http\Controllers\AtencionUsuario;

use App\Http\Requests\AtencionUsuario\TipincapacidadRequest;
use App\Models\AtencionUsuario\AuTipincapacidade;
use App\Models\AtencionUsuario\AuTipprestacione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class TipincapacidadController extends Controller
{

    public function index()
    {
        return AuTipprestacione::where('nomenclatura','<>',null)->with('tipos')->get();
    }


    public function store(TipincapacidadRequest $request)
    {
        $tipincapacidad = AuTipincapacidade::create($request->all());

        return (new Resource($tipincapacidad))->response()->setStatusCode(201);
    }


    public function update(TipincapacidadRequest $request, AuTipincapacidade $tipincapacidade)
    {
        $tipincapacidade->update($request->all());

        return (new Resource($tipincapacidade))->response()->setStatusCode(202);
    }

    public function destroy(AuTipincapacidade $tipincapacidade)
    {
        try {
            $tipincapacidade->delete();
            return response()->json('Aceptado',202);
        } catch (\Exception $e) {
            return $e;
        }
    }


}
