<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\CuentasMedicas\CmPatologiatrazadora;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class PatologiaTrazadoraController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $patologia_trazadora = CmPatologiatrazadora::pimp()
                ->orderBy('nombre','asc')
                ->paginate(Input::get('per_page'));
            return new Resource($patologia_trazadora);
        }
        $patologia_trazadora = CmPatologiatrazadora::pimp()
            ->orderBy('nombre','asc')
            ->limit(100)
            ->get();
        return new Resource($patologia_trazadora);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required']);
        $patologia_trazadora = CmPatologiatrazadora::create($request->all());
        return new Resource($patologia_trazadora);
    }

    public function show($id)
    {
        return new Resource(CmPatologiatrazadora::where('id',$id)->get());
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required']);
        $patologia_trazadora = CmPatologiatrazadora::find($id);
        $patologia_trazadora->update($request->all());
        return new Resource($patologia_trazadora);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'DeleteRestrict']);
    }
}
