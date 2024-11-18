<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/03/2019
 * Time: 2:47 PM
 */

namespace App\Capresoca\Aseguramiento;


use App\Http\Requests\NovedadesRules;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTipnovedade;
use App\Models\Aseguramiento\AsTramite;
use App\Models\General\GnEmpresa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NovedadAutomaticaSinAnterior
{
    use NovedadesRules;


    protected $afiliado;
    protected $codigoNovedad;
    protected $variables;


    /**
     * NovedadAutomaticaSinAnterior constructor.
     * @param AsAfiliado $afiliado
     * @param $codigoNovedad
     * @param array $variables
     * @throws ValidationException
     * @throws \Exception
     */
    public function __construct(AsAfiliado $afiliado, $codigoNovedad, Array $variables)
    {
        $this->afiliado = $afiliado;
        $this->codigoNovedad = $codigoNovedad;
        $this->variables = $variables;
        $this->validar();
        return $this->crear();
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    private function validar()
    {
        $codigoNovedad = $this->codigoNovedad;
        if(!method_exists($this,$codigoNovedad)){
            throw new \Exception('Codigo de novedad invalido');
        }

        $validator = Validator::make($this->variables, $this->$codigoNovedad(), $this->messages() );

        if($validator->fails()){
            throw new ValidationException($validator);
        }
    }

    private function crear()
    {

        $empresa = GnEmpresa::first();

        $novtramite_base = [
            'as_tramite_id' => $this->createTramite()->id,
            'as_tipnovedade_id' => AsTipnovedade::whereCodigo($this->codigoNovedad)->first()->id,
            'as_afiliado_id' => $this->afiliado->id,
            'gn_municipio_id' => $this->afiliado->gn_municipio_id,
            'gn_tipdocidentidad_id' => $this->afiliado->gn_tipdocidentidad_id,
            'codigo_entidad' => $this->afiliado->regimene_id === 1 ? $empresa->codhabilitacion_contrib : $empresa->codhabilitacion_subsid,
            'identificacion' => $this->afiliado->identificacion,
            'apellido1' => $this->afiliado->apellido1,
            'apellido2' => $this->afiliado->apellido2,
            'nombre1' => $this->afiliado->nombre1,
            'nombre2' => $this->afiliado->nombre2,
            'fecha_nacimiento' => $this->afiliado->fecha_nacimiento,
            'fecha_ini_novedad'=> today()->toDateString(),
        ];

        $novt = array_merge($novtramite_base,$this->variables);

        $novtramite = AsNovtramite::create($novt);

        return $novtramite;
    }

    private function createTramite()
    {
        $regimen = $this->afiliado->regimen->nombre;

        return AsTramite::create([
            'tipo_tramite'  => "Novedad $regimen",
            'clase_tramite' => 'Automatico',
            'estado'        => 'Radicado'
        ]);
    }



}