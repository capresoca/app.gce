<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/06/2018
 * Time: 8:27 AM
 */

namespace App\Http\Repositories\Aseguramiento;


use App\Http\Repositories\Repository;
use App\Http\Repositories\TerceroRepository;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsTipafiliacione;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PagadorRepository implements Repository
{


    public function guardar($requestArray)
    {
            $repoTercero = new TerceroRepository();
            $tercero = $repoTercero->guardar($requestArray['tercero']);
            $requestArray['gn_tercero_id'] = $tercero->id;
            array_forget($requestArray, 'tercero');

            if($tercero->gn_tipdocidentidad_id === 1){
                $requestArray['razon_social'] = $tercero->nombre_completo;
            }

            if(isset($requestArray['id'])){
                $pagadorEdit = AsPagadore::find($requestArray['id']);
                $pagadorEdit->fill($requestArray);
                $pagadorEdit->save();
                return $pagadorEdit;
            }
            $pagador = new AsPagadore();
            $pagador->fill($requestArray);
            $pagador->save();
            return $pagador;

    }

    public function validar($requestArray)
    {


        $rules = [
            'as_tipaportante_id' => 'required|exists:as_tipaportantes,id',
            'tercero' => 'required'
        ];

        if(isset($requestArray['tercero']) && $requestArray['tercero'] != []){
            $repoTercero = new TerceroRepository();
            $repoTercero->validar($requestArray['tercero']);
        }

        $validator = Validator::make($requestArray,$rules);

        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }
    }
}