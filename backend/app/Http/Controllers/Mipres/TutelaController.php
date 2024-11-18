<?php

namespace App\Http\Controllers\Mipres;

use App\Http\Resources\Mipres\TutelasResource;
use App\Models\Mipres\MpTutela;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class TutelaController extends Controller
{
    public function index(){


        if(Input::get('per_page')){
            return TutelasResource::collection(
                MpTutela::withAll()->pimp()
                    ->orderBy('id','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            MpTutela::withAll()
                ->pimp()->limit(1000)->orderBy('updated_at','desc')->get()
        );

    }

    public function show(MpTutela $mptutela)
    {
        $mptutela->load($mptutela->allRelations());
        return new Resource($mptutela);
    }
}
