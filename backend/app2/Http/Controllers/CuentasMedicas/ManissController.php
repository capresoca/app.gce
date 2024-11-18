<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Models\CuentasMedicas\CmManiss;
use App\Models\CuentasMedicas\CmManisse;
use App\Models\CuentasMedicas\CmManisss;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class ManissController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return Resource::collection(
                CmManisss::pimp()
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(CmManisss::pimp()->orderBy('updated_at', 'desc')->get());
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
