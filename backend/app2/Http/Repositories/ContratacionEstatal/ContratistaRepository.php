<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/06/2018
 * Time: 8:27 AM
 */

namespace App\Http\Repositories\ContratacionEstatal;


use App\Http\Repositories\Repository;
use App\Http\Repositories\TerceroRepository;
use App\Models\Aseguramiento\AsTipafiliacione;
use App\Models\ContratacionEstatal\CeContratista;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ContratistaRepository implements Repository
{

    public function guardar($requestArray)
    {
        try{
            $repoTercero = new TerceroRepository();
            $tercero = $repoTercero->guardar($requestArray['tercero']);
            $requestArray['gn_tercero_id'] = $tercero->id;
            array_forget($requestArray, 'tercero');
            if(isset($requestArray['id'])){
                $ce_contratistaEdit = CeContratista::find($requestArray['id']);
                $ce_contratistaEdit->fill($requestArray);
                $ce_contratistaEdit->save();
                return $ce_contratistaEdit;
            }
            $ce_contratista = new CeContratista();
            $ce_contratista->fill($requestArray);
            $ce_contratista->save();
            return $ce_contratista;
        }
        catch (\Exception $e){
            throw new \Exception($e);
        }
    }

    public function validar($requestArray)
    {

        $rules = [
            'ccomercio' => 'required|max:45',
            'fecha_ccomercio' => 'required|date',
            'rep_legal' => 'required|max:500',
            'gn_tercero_id' => 'required|exists:gn_terceros,id',
            'gn_munccomercio_id' => 'required|exists:gn_municipios,id',
            'ce_natjuridica_id' => 'required|exists:ce_natjuridicas,id',
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