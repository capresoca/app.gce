<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Requests\CuentasMedicas\ConcondclinicasRequest;
use App\Models\CuentasMedicas\CmConcondclinica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class ConcondclinicasController extends Controller
{
    public function index()
    {
        return new Resource(CmConcondclinica::orderBy('nombre','asc')->get());
    }

    public function store(ConcondclinicasRequest $request)
    {
        $condicion_clinica = CmConcondclinica::create($request->all());
        return new Resource($condicion_clinica);
    }

    public function update(ConcondclinicasRequest $request, $id)
    {
        $condicion_clinica = CmConcondclinica::find($id);
        $condicion_clinica->update($request->all());
        return new Resource($condicion_clinica);
    }
}
