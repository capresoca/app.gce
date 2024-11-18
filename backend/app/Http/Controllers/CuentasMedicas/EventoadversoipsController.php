<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\CuentasMedicas\CmEventoadversoips;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class EventoadversoipsController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')) {
            $evento_adverso_ips = CmEventoadversoips::pimp()
                ->orderBy('nombre','asc')
                ->paginate(Input::get('per_page'));
            return new Resource($evento_adverso_ips);
        }
        $evento_adverso_ips = CmEventoadversoips::pimp()
            ->orderBy('nombre','asc')
            ->limit(100)
            ->get();
        return new Resource($evento_adverso_ips);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required']);
        $evento_adverso_ips = CmEventoadversoips::create($request->all());
        return new Resource($evento_adverso_ips);
    }

    public function show($id)
    {
        return new Resource(CmEventoadversoips::where('id',$id)->get());
    }

    public function update(Request $request, $id)
    {
        $request->validate(['codigo' => 'required','nombre' => 'required']);
        $evento_adverso_ips = CmEventoadversoips::find($id);
        $evento_adverso_ips->update($request->all());
        return new Resource($evento_adverso_ips);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'DeleteRestrict']);
    }
}
