<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/05/2018
 * Time: 8:45 AM
 */

namespace App\Http\Repositories\Aseguramiento;


use App\Http\Repositories\Repository;
use App\Http\Repositories\TerceroRepository;
use App\Models\Aseguramiento\AsAfiliacione;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfitramite;
use App\Models\Aseguramiento\AsTramite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AfitramiteRepository implements Repository
{

    public function guardar($requestArray)
    {
        if($requestArray['recien_nacido']){
            return $this->guardarRecienNacido($requestArray);
        }
        return $this->guardarAfitramite($requestArray);

    }

    private function radicar($afitramite){
        $fecha_afiliacion = $afitramite->tramite->fecha_radicacion;
        $afitramite->tramite->no_radicado = AsTramite::all()->pluck('no_radicado')->last() + 1;
        $cabeza = $afitramite->afiliado;
        $cabeza->fecha_afiliacion = $fecha_afiliacion;
        $cabeza->save();

        foreach ($afitramite->beneficiarios as $beneficiario){
            $beneficiario->fecha_afiliacion = $fecha_afiliacion;
            $beneficiario->cabfamilia_id = $cabeza->id;
            $beneficiario->as_parentesco_id = $beneficiario->pivot->as_parentesco_id;
            $beneficiario->save();
            $this->makeTramiteBeneficiario($afitramite, $beneficiario);
        }
    }

    private function makeTramiteBeneficiario($afitramite, $beneficiario){
        $tramite = AsTramite::create([
            'tipo_tramite' => $afitramite->tramite->tipo_tramite,
            'consecutivo' => AsTramite::all()->pluck('consecutivo')->last() + 1,
            'clase_tramite' => 'Manual',
            'fecha_radicacion' => $afitramite->tramite->fecha_radicacion,
            'fecha' => $afitramite->tramite->fecha,
            'no_radicado' => $afitramite->tramite->no_radicado,
            'gs_usuario_id' => Auth::user()->id
        ]);

        AsAfitramite::create([
            'as_tramite_id' => $tramite->id,
            'as_regimene_id' => $afitramite->as_regimene_id,
            'as_tipafiliacione_id' => $afitramite->as_tipafiliacione_id,
            'as_tipafiliado_id' => $afitramite->as_tipafiliado_id,
            'as_afiliado_id' => $beneficiario->id
        ]);

    }

    private function guardarAfitramite($requestArray){
        $this->validar($requestArray);
        $repoTramite = new TramiteRepository();
        $tramite = $repoTramite->guardar($requestArray['tramite']);
        $requestArray['as_tramite_id'] = $tramite->id;

        if($requestArray['id'] != null){
            try{

                $afitramiteAEditar = AsAfitramite::find($requestArray['id']);
                $afitramiteAEditar->update($requestArray);
                if($tramite->estado === 'Radicado'){
                    $this->radicar($afitramiteAEditar);
                }
                return $afitramiteAEditar;
            }catch ( \Exception $e)
            {
                return $e;
            }

        }

        $afitramite = AsAfitramite::create($requestArray);
        $afitramite->afiliado->estado = 2;
        $afitramite->afiliado->save();

        foreach ($afitramite->beneficiarios as $beneficiario){
            $beneficiario->estado = 2;
            $beneficiario->save();
        }

        if($tramite->estado === 'Radicado'){
            $this->radicar($afitramite);
        }

        return $afitramite;
    }

    private function guardarRecienNacido($requestArray){
        $this->validar($requestArray);



    }

    public function validar($requestArray)
    {
        $rules = [
            'as_regimene_id' => 'required',
            'as_tipafiliado_id' => 'required',
            'rs_ips_id'=>'required',
        ];

        if($requestArray['as_regimene_id'] === 1){
            $rules['as_tipcotizante_id'] = 'required|exists:as_tipcotizantes,id';
            $rules['as_clasecotizante_id'] = 'required|exists:as_clasecotizantes,id';
            $rules['ibc'] = 'required';
            $rules['as_tipafiliacione_id'] = 'required|min:1|max:5';
        }


        if(isset($requestArray['tramite'])&& $requestArray['tramite'] != []){
            $repoAfiliado = new TramiteRepository();
            $repoAfiliado->validar($requestArray['tramite']);
        }else{
            $rules['as_tramite_id'] = 'required';
        }

        if(isset($requestArray['afiliado'])&& $requestArray['afiliado'] != []){
            $repoAfiliado = new AfiliadoRepository();
            $repoAfiliado->validar($requestArray['afiliado']);
        }else{
            $rules['as_afiliado_id'] = 'required';
        }

        if(isset($requestArray['conyuge'])&& $requestArray['conyuge'] != []){
            $repoAfiliado = new AfiliadoRepository();
            $repoAfiliado->validar($requestArray['conyuge']);
        }
        if(isset($requestArray['pagador'])&& $requestArray['pagador'] != []){
            $repoPagador = new PagadorRepository();
            $repoPagador->validar($requestArray['pagador']);
        }


        $validator = Validator::make($requestArray,$rules);
        if($validator->fails()){
            throw new ValidationException($validator->errors());
        }

    }

}