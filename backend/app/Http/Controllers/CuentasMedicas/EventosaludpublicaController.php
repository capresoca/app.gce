<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\CuentasMedicas\CmEventosaludpublica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class EventosaludpublicaController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')) {
            $evento_salud_publica = CmEventosaludpublica::pimp()
                ->orderBy('nombre','asc')
                ->paginate(Input::get('per_page'));
            return new Resource($evento_salud_publica);
        }
        $evento_salud_publica = CmEventosaludpublica::pimp()
            ->orderBy('nombre','asc')
            ->limit(100)
            ->get();
        return new Resource($evento_salud_publica);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required']);
        $evento_salud_publica = CmEventosaludpublica::create($request->all());
        return new Resource($evento_salud_publica);
    }

    public function show($id)
    {
        return new Resource(CmEventosaludpublica::where('id',$id)->get());
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required']);
        $evento_salud_publica = CmEventosaludpublica::find($id);
        $evento_salud_publica->update($request->all());
        return new Resource($evento_salud_publica);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'DeleteRestrict']);
    }
}
