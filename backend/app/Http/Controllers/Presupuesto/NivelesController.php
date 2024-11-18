<?php

namespace App\Http\Controllers\Presupuesto;

use App\Models\Presupuesto\PrNiveles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Validation\Rule;

class NivelesController extends Controller
{
    public function index()
    {
        return new Resource(PrNiveles::all());
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules($request));

        $nivel = PrNiveles::create($request->all());

        return (new Resource($nivel))->response()->setStatusCode(201);

    }

    public function update(PrNiveles $nivele, Request $request)
    {
        $this->validate($request,$this->getValidationRules($request));
        $nivele->update($request->all());
    }

    public function destroy(PrNiveles $nivele)
    {
        try {

            if(!$nivele->removable) return response()->json('tiene conceptos, no se puede eliminar',422);

            $nivele->delete();

            return new Resource(PrNiveles::all());

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getValidationRules(Request $request)
    {
        return [
            'nombre' => 'required|string|max:500',
            'digitos' => 'required|numeric|max:30'
        ];
    }
}
