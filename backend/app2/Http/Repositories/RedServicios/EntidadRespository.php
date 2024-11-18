<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26/06/2018
 * Time: 4:46 PM
 */

namespace App\Http\Repositories\RedServicios;


use App\Http\Repositories\Repository;
use App\Http\Repositories\TerceroRepository;
use App\Models\RedServicios\RsEntidade;
use App\Traits\EnumsTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EntidadRespository implements Repository
{

    public function guardar($requestArray)
    {
        try{
            $repoTercero = new TerceroRepository();
            if(!isset($requestArray['gn_tercero_id']) || $requestArray['gn_tercero_id'] === '')
            {
                $tercero = $repoTercero->guardar($requestArray['tercero']);
                $requestArray['gn_tercero_id'] = $tercero->id;
            }

            if(!isset($requestArray['gerente_id']) || $requestArray['gerente_id'] === ''){
                $gerente = $repoTercero->guardar($requestArray['gerente']);
                $requestArray['gerente_id'] = $gerente->id;
            }
            if(!isset($requestArray['replegal_id']) || $requestArray['replegal_id'] === '') {
                $replegal = $repoTercero->guardar($requestArray['replegal']);
                $requestArray['replegal_id'] = $replegal->id;
            }

            if(isset($requestArray['id'])){
                $entidadEdicion = RsEntidade::findOrFail($requestArray['id']);
                try{
                    $entidadEdicion->update($requestArray);
                }catch (\Exception $e){
                    return $e;
                }
                return $entidadEdicion;
            }
            $entidad = new RsEntidade;
            $entidad->fill($requestArray);
            $entidad->save();
            return $entidad;
        }
        catch (\Exception $e){
            throw new \Exception($e);
        }
    }

    /**
     * @param $requestArray
     * @throws ValidationException
     */
    public function validar($requestArray)
    {
        $tipos = EnumsTrait::getEnumValues('rs_entidades','tipo');

        $rules = [
            'tipo' => [
                'required',
                Rule::in($tipos)
            ],
            'tercero' => 'required'
        ];

        if(isset($requestArray['tercero']) && $requestArray['tercero'] !== []){
            $repoTercero = new TerceroRepository();
            $repoTercero->validar($requestArray['tercero']);
        }
        else{
            $rules['gn_tercero_id'] = 'required';
            $rules['tercero'] = '';
        }

        if(isset($requestArray['gerente']) && $requestArray['gerente'] !== []){
            $repoTercero2 = new TerceroRepository();
            $repoTercero2->validar($requestArray['gerente']);
        }

        if(isset($requestArray['replegal']) && $requestArray['replegal'] !== []){
            $repoTercero3 = new TerceroRepository();
            $repoTercero3->validar($requestArray['replegal']);
        }



        $validator = Validator::make($requestArray,$rules);

        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }
    }
}