<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/06/2018
 * Time: 3:47 PM
 */

namespace App\Http\Repositories\Aseguramiento;


use App\Http\Repositories\Repository;
use App\Models\Aseguramiento\AsNucfamiliare;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class NucFamiliarRepository implements Repository
{

    public function guardar($requestArray)
    {
        try{

            $repoAfiliado = new AfiliadoRepository();
            $afiliado = $repoAfiliado->guardar($requestArray['afiliado']);
            $requestArray['as_beneficiario_id'] = $afiliado->id;
            array_forget($requestArray, 'afiliado');


            if(isset($requestArray['id'])){
                $beneficiarioEdit = AsNucfamiliare::find($requestArray['id']);
                $beneficiarioEdit->fill($requestArray);
                $beneficiarioEdit->save();
                return $beneficiarioEdit;
            }

            $beneficiario = AsNucfamiliare::create($requestArray);



            return $beneficiario;
        }
        catch (\Exception $e){
            throw new \Exception($e);
        }
    }

    public function validar($requestArray)
    {

        $rules = [
            'rs_entidade_id' => 'required|exists:rs_entidades,id',
            'as_parentesco_id' => 'required|exists:as_parentescos,id',
            'afiliado' => 'required'
        ];

        $validator = Validator::make($requestArray,$rules);
        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }
    }
}