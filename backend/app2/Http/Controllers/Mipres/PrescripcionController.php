<?php

namespace App\Http\Controllers\Mipres;

use App\Http\Resources\Mipres\PrescripcionResource;
use App\Mail\PrescripcionAprobada;
use App\Models\Mipres\MpPrescripcione;
use App\Http\Controllers\Controller;
use App\Models\RedServicios\RsEntidade;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class PrescripcionController extends Controller
{
    public function index(){

        if(Input::get('per_page') || Input::get('all')){
            return PrescripcionResource::collection(
                MpPrescripcione::pimp()
                    ->orderBy('id','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            MpPrescripcione::withAll()
                ->pimp()->limit(1000)->orderBy('updated_at','desc')->get()
        );

    }

    public function show(MpPrescripcione $prescripcione)
    {
        $prescripcione->load($prescripcione->allRelations());
        return new Resource($prescripcione);
    }

    public function update(MpPrescripcione $prescripcione, Request $request)
    {
        $rules = [
            'estado' => 'required',
            'farmacia_id' => $request->estado === 'Aprobado' ? 'required' :''
        ];

        $this->validate($request,$rules);

        if($request->estado === 'Aprobado' && $prescripcione->estado != 'Aprobado'){

            foreach ($request->entregas as $entrega) {

            }
        }

        $prescripcione->update($request->all());

        return new Resource($prescripcione);

    }

    public function mail(MpPrescripcione $prescripcione)
    {
        return view('mail.prescripcion_aprobada',['prescripcion' => $prescripcione]);
    }
}
