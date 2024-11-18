<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Resources\AtencionUsuario\pqrsdResource;
use App\Models\Autorizaciones\Refcup;
use App\Models\RedServicios\RsOtrosentidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

class OtrosentidadController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return  Resource::collection(
                RsOtrosentidade::pimp()
                    ->paginate(Input::get('per_page'))
            );
        }
    }

    public function show(RsOtrosentidade $otrosentidade)
    {
        return new Resource($otrosentidade);
    }

    public function store(Request $request)
    {

        $this->validate($request,$this->validationRules($request));

        $otroEntidad = RsOtrosentidade::create($request->all());
        $this->createOrUpdateRefCups($otroEntidad);

        return (new Resource($otroEntidad))
            ->response()
            ->setStatusCode(201);

    }


    public function update(RsOtrosentidade $otrosentidade,Request $request)
    {
        $this->validate($request,$this->validationRules($request));

        $otrosentidade->update($request->all());

        return (new Resource($otrosentidade))
            ->response()
            ->setStatusCode(202);
    }

    private function validationRules($request)
    {
        return [
            'rs_entidades_id' => 'required|exists:rs_entidades,id',
            'codigo' => [
                'required',
                Rule::unique('rs_otrosentidades')->ignore($request->id)
            ],
            'nombre' => 'required|string|max:500',
        ];
    }
    
    private function createOrUpdateRefCups ($data) {
        $insert = [
            'codigo'    => $data['codigo'],
            'homologo'  => $data['codigo'],
            'descrip'   => $data['nombre'],
            'genero'    => 'A',
            'ambito'    => 1,
            'lInf'      => 0,
            'valor'     => $data['tarifa'],
            'lSup'      => 0,
            'AT'        => '1',
            'pos'       => '1',
            'altoCosto' => '1',
            'cober'     => '94',
            'Qx'        => '1',
            'aut'       => '',
            'freq'      => '',
            'ind'       => '1',
            'fi'        => '2018-03-01',
            'ff'        => '2099-03-01'
        ];
        $refcup = Refcup::where('codigo',$data['codigo'])->first();
        if (! isset($refcup)) {
            Refcup::create($insert);
        }
    }
}
