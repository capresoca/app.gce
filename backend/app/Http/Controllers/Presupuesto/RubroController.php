<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Resources\Presupuesto\RubroResource;
use App\Models\Presupuesto\PrRubro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class RubroController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            $pr_rubros = PrRubro::with('fuenrecursos', 'presupuesto')->pimp()->orderBy('updated_at', 'desc')->paginate(Input::get('per_page'));
            return RubroResource::collection($pr_rubros);
        }
        return RubroResource::collection(PrRubro::with('fuenrecursos', 'presupuesto')->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
