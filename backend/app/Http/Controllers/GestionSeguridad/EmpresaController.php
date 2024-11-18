<?php

namespace App\Http\Controllers\GestionSeguridad;


use App\Http\Requests\GestionSeguridad\EmpresaRequest;
use App\Http\Resources\GestionSeguridad\EmpresaResource;
use App\Models\General\GnEmpresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmpresaController extends Controller
{

    public function index()
    {
        $empresa = GnEmpresa::with(GnEmpresa::allRelations())->first();

        if ($empresa) {
            return response()->json([
                'exists' => true,
                'data' => new EmpresaResource($empresa)
            ], 200);
        }

        return response()->json(
            [
                'exists' => false,
                'message' => 'Aún no se ha realizado el registro de empresa.'
            ]
        );
    }

    public function store(EmpresaRequest $request)
    {
        $empresa = GnEmpresa::create($request->all());
        $empresa->load(GnEmpresa::allRelations());
        return new EmpresaResource($empresa);
    }

    public function update(EmpresaRequest $request, GnEmpresa $empresa)
    {
        $empresa->update($request->all());
        $empresa->load(GnEmpresa::allRelations());
        return new EmpresaResource($empresa);
    }

//    public function show(GnEmpresa $empresa)
//    {
//        return new EmpresaResource($empresa->load('repLegal', 'dian', 'tesoreria', 'tercero',
//            'gerente', 'cargo', 'deficit', 'ganancias', 'superavit',
//            'comCierre', 'comDistribucion', 'comAjuste', 'comHomologacion',
//            'comTraslado'));
//    }
}
