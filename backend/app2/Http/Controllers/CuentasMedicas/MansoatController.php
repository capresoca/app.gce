<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\CuentasMedicas\CmMansoat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class MansoatController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return Resource::collection(
                CmMansoat::pimp()
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(CmMansoat::pimp()->orderBy('updated_at', 'desc')->get());
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
