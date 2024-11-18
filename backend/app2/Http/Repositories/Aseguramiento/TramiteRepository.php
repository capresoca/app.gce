<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/05/2018
 * Time: 8:53 AM
 */

namespace App\Http\Repositories\Aseguramiento;


use App\Http\Repositories\Repository;
use App\Models\Aseguramiento\AsTramite;
use App\Traits\EnumsTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class TramiteRepository implements Repository
{

    public function guardar($requestArray)
    {
        try{

            $requestArray['gs_usuario_id'] =  Auth::user()->id;

            if($requestArray['estado'] === 'Radicado'){
                $requestArray['gs_usuradica_id'] = Auth::user()->id;
            }

            if(isset($requestArray['id'])){
                $tramiteAEditar = AsTramite::find($requestArray['id']);
                $tramiteAEditar->fill($requestArray);
                $tramiteAEditar->save();
                return $tramiteAEditar;
            }

            $requestArray['consecutivo'] = AsTramite::all()->pluck('consecutivo')->last() + 1;
            $tramiteAEditar = new AsTramite();

            $requestArray['estado'] = ($requestArray->fosyga === true) ? 'Radicado' : 'Aprobado';

            $tramiteAEditar->fill($requestArray);
            $tramiteAEditar->save();
            return $tramiteAEditar;
        }catch (\Exception $e){
            throw new \Exception($e);
        }
    }



    public function validar($requestArray)
    {
        $tipos_tramite = EnumsTrait::getEnumValues('as_tramites', 'tipo_tramite');
        $clases_tramite = EnumsTrait::getEnumValues('as_tramites','clase_tramite');
        $estados = EnumsTrait::getEnumValues('as_tramites','estado');
        $rules = [
            'tipo_tramite' => 'required',
            'fecha' => 'required|date',
            'clase_tramite' => 'required',
            'estado' => 'required',
        ];
        switch ($requestArray['estado']){
            case 'Radicado':
                $rules['fecha_radicacion'] = 'required|date';
                break;
            case 'Validado':
                $rules['fecha_validacion'] = 'required|date';
                $rules['valido'] = 'required|in:Si,No';
                break;
            case 'Enviado':
                $rules['fecha_envio'] = 'required|date';
                break;
            case 'Glosado':
                $rules['fecha_glosa'] = 'required|date';
                $rules['valido'] = 'required|in:Si,No';
                break;
        }
        $validator = Validator::make($requestArray,$rules);
        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }
    }
}

