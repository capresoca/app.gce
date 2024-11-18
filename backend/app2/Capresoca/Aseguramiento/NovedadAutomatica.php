<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13/09/2018
 * Time: 2:39 PM
 */

namespace App\Capresoca\Aseguramiento;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTipnovedade;
use App\Models\General\GnMunicipio;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Traits\ToolsTrait;

class NovedadAutomatica
{
    protected $afiliado;
    protected $nuevaNovedad;
    protected $novedadOriginal;

    public function __construct(AsNovtramite $novtramite)
    {
        $this->afiliado = $novtramite->afiliado;
        $this->tipo = AsTipnovedade::find($novtramite->as_tipnovedade_id);
        $this->novedadOriginal = $novtramite;
        $metodoNovedad = $this->tipo->codigo;

        if(method_exists($this,$metodoNovedad))
        {
            $this->$metodoNovedad();
        }else{
            Log::info('Metodo Novedad Automatica: '.$metodoNovedad);
            throw new \Exception('El metodo de la novedad Automatica aun no esta creado: '.$metodoNovedad);
        }
    }

    public function getNuevaNovedad(){
        if($this->nuevaNovedad)
            return $this->nuevaNovedad;

        return false;
    }

    private function N01()
    {
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $this->afiliado->tipo_id->abreviatura;
        $this->nuevaNovedad['v2'] = $this->afiliado->identificacion;

        $this->nuevaNovedad['v3'] = ToolsTrait::homologarFecha($this->afiliado->fecha_nacimiento);
        $this->nuevaNovedad['v4'] = $this->novedadOriginal->v4;
    }

    private function N02()
    {
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $this->afiliado->nombre1;
        $this->nuevaNovedad['v2'] = $this->afiliado->nombre2;
    }

    private function N03()
    {
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $this->afiliado->apellido1;
        $this->nuevaNovedad['v2'] = $this->afiliado->apellido2;
    }
    private function N04()
    {
        $this->nuevaNovedad = $this->baseNovedad();
        $municipioAfiliado=GnMunicipio::where('id',$this->afiliado->gn_municipio_id)->with('departamento')->first();
        if(!$municipioAfiliado){
            throw new \Exception('Codigo de municipio no relacionado con el afiliado, NOVEDAD N04.');
        }
        $this->nuevaNovedad['v1'] = $municipioAfiliado->departamento->codigo;
        $this->nuevaNovedad['v2'] = $municipioAfiliado->codigo;
    }
    private function N05()
    {
        $this->nuevaNovedad = $this->baseNovedad();
        $cotizanteAfiliado=AsAfiliado::where('id',$this->afiliado->cabfamilianterior_id)->with('tipo_id')->first();
        $tipoDocumentoCotizante='';
        $identificacionCotizante='';
        if($cotizanteAfiliado){
            $tipoDocumentoCotizante=$cotizanteAfiliado->tipo_id->abreviatura;
            $identificacionCotizante=$cotizanteAfiliado->identificacion;
        }
        $this->nuevaNovedad['v1'] = $tipoDocumentoCotizante;
        $this->nuevaNovedad['v2'] = $identificacionCotizante;
    }
    private function N06()
    {
            $aportante = AsAfiliadoPagador::where('as_afiliado_id',$this->afiliado->id)->with(['pagador.tercero.tipo_id'])->first();
            $tipoDocumentoAportante = '';
            $identificacionAportante = '';
            if(!$aportante){
                if($aportante->pagador){
                    if($aportante->pagador->tercero){
                        $tipoDocumentoAportante = $aportante->pagador->tercero->tipo_id->abreviatura;
                        $identificacionAportante = $aportante->pagador->identificacion;
                    }
                }
            }
            $datosRelacionadosDelAfiliado=$this->afiliado->with(['clase_cotizante','ciiu'])->first();
            $codigoCiiu='';
            $claseCotizante='';
            if($datosRelacionadosDelAfiliado->ciiu){
                $codigoCiiu=$datosRelacionadosDelAfiliado->ciiu->codigo;
            }
            if($datosRelacionadosDelAfiliado->clase_cotizante){
                $claseCotizante=$datosRelacionadosDelAfiliado->clase_cotizante->codigo;
            }
            $this->nuevaNovedad = $this->baseNovedad();
            $this->nuevaNovedad['v1'] = $claseCotizante;
            $this->nuevaNovedad['v2'] = $tipoDocumentoAportante;
            $this->nuevaNovedad['v3'] = $identificacionAportante;
            $this->nuevaNovedad['v4'] = $codigoCiiu;
    }
    private function N07()
    {

        $cotizante=AsAfiliado::where('id',$this->afiliado->cabfamilia_id)->with(['tipo_id'])->first();
        $tipoDocCotizante='';
        $identificacionCotizante='';
        if($cotizante){
            $tipoDocCotizante=$cotizante->tipo_id->abreviatura;
            $identificacionCotizante=$cotizante->tipo_id->abreviatura;
        }
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['tipo_afiliado','parentesco','condicion_discapacidad'])->first();
        $condicionDeDiscapacidad='';
        $tipoAfiliado='';
        if($datosRelacionadosDelAfiliado->condicion_discapacidad){
            $condicionDeDiscapacidad=$datosRelacionadosDelAfiliado->condicion_discapacidad->abreviatura;
        }
        if($datosRelacionadosDelAfiliado->tipo_afiliado){
            $tipoAfiliado=$datosRelacionadosDelAfiliado->tipo_afiliado->codigo;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $tipoDocCotizante;
        $this->nuevaNovedad['v2'] = $identificacionCotizante;
        $this->nuevaNovedad['v3'] = $tipoAfiliado;
        $this->nuevaNovedad['v4'] = $datosRelacionadosDelAfiliado->parentesco->codigo;
        $this->nuevaNovedad['v5'] = $condicionDeDiscapacidad;
    }
    ///No existen ejemplos para probrar
    private function N08()
    {

        $datosRelacionadosDelAfiliado = $this->afiliado->with(['clase_cotizante','ciiu'])->first();
        $codigoCiiu='';
        $tipoCotizante='';
        if($datosRelacionadosDelAfiliado->ciiu){
            $codigoCiiu=$datosRelacionadosDelAfiliado->ciiu->codigo;
        }
        if($datosRelacionadosDelAfiliado->clase_cotizante){
            $tipoCotizante=$datosRelacionadosDelAfiliado->clase_cotizante->codigo;
        }

        $aportante = AsAfiliadoPagador::where('as_afiliado_id',$this->afiliado->id)->with(['pagador.tercero.tipo_id'])->first();
        $tipoDocumentoAportante='';
        $identificacionAportante='';
        if($aportante->pagador){
            if($aportante->pagador->tercero){
                $tipoDocumentoAportante=$aportante->pagador->tercero->tipo_id->abreviatura;
                $identificacionAportante=$aportante->pagador->identificacion;
            }
        }

        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $tipoCotizante;
        $this->nuevaNovedad['v2'] = $tipoDocumentoAportante;
        $this->nuevaNovedad['v3'] = $identificacionAportante;
        $this->nuevaNovedad['v4'] = $codigoCiiu;
    }
    private function N09()
    {
        $this->nuevaNovedad = $this->baseNovedad();
    }
    private function N10()
    {
        $aportante = AsAfiliadoPagador::where('as_afiliado_id',$this->afiliado->id)->with(['pagador.tercero.tipo_id'])->first();
        $tipoIdentificacionAportante='';
        $identificacionAportante='';
        if($aportante->pagador){
            if($aportante->pagador->tercero){
                $tipoIdentificacionAportante=$aportante->pagador->tercero->tipo_id->abreviatura;
                $identificacionAportante=$aportante->pagador->identificacion;
            }
        }
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['clase_cotizante'])->first();
        $claseCotizante='';
        if($datosRelacionadosDelAfiliado->clase_cotizante){
            $claseCotizante=$datosRelacionadosDelAfiliado->clase_cotizante->codigo;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $tipoIdentificacionAportante;
        $this->nuevaNovedad['v2'] = $identificacionAportante;
        $this->nuevaNovedad['v3'] = $claseCotizante;
        $this->nuevaNovedad['v4'] = $this->novedadOriginal->v4;
        $this->nuevaNovedad['v5'] = $this->novedadOriginal->v5;
        $this->nuevaNovedad['v6'] = $this->novedadOriginal->v6;
    }
    private function N11()
    {
        $aportante = AsAfiliadoPagador::where('as_afiliado_id',$this->afiliado->id)->with(['pagador.tercero.tipo_id'])->first();
        $tipoIdentificacionAportante='';
        $identificacionAportante='';
        if($aportante->pagador){
            if($aportante->pagador->tercero){
                $tipoIdentificacionAportante=$aportante->pagador->tercero->tipo_id->abreviatura;
                $identificacionAportante=$aportante->pagador->identificacion;
            }
        }
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['clase_cotizante'])->first();
        $claseCotizante='';
        if($datosRelacionadosDelAfiliado->clase_cotizante){
            $claseCotizante=$datosRelacionadosDelAfiliado->clase_cotizante->codigo;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $tipoIdentificacionAportante;
        $this->nuevaNovedad['v2'] = $identificacionAportante;
        $this->nuevaNovedad['v3'] = $claseCotizante;
    }
    private function N12()
    {
        
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['condicion_discapacidad'])->first();
        $condicionDiscapacidad='';
        if($datosRelacionadosDelAfiliado->condicion_discapacidad){
            $condicionDiscapacidad=$datosRelacionadosDelAfiliado->condicion_discapacidad->codigo;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $condicionDiscapacidad;
    }
    private function N13()
    {
        
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['condicion_terminacion'])->first();
        $codigoDeTerminacion='';
        if($datosRelacionadosDelAfiliado->condicion_terminacion){
            $codigoDeTerminacion=$datosRelacionadosDelAfiliado->condicion_terminacion->codigo;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $codigoDeTerminacion;
        $this->nuevaNovedad['v2'] = '';  //Fecha de fin de periodo solicitado, no se sabe cual variable va aqui
    }
    private function N14()
    {
        
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['condicion_terminacion','estado_afiliado'])->first();
        $codigoDeTerminacion='';
        $estadoAfiliacion='';
        if($datosRelacionadosDelAfiliado->condicion_terminacion){
            $codigoDeTerminacion=$datosRelacionadosDelAfiliado->condicion_terminacion->codigo;
        }
        if($datosRelacionadosDelAfiliado->estado_afiliado){
            $estadoAfiliacion=$datosRelacionadosDelAfiliado->estado_afiliado->codigo;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $codigoDeTerminacion;
        $this->nuevaNovedad['v2'] = $estadoAfiliacion;
    }
    private function N15()
    {
        $this->nuevaNovedad = $this->baseNovedad();
    }
    private function N16()
    {
        $this->nuevaNovedad = $this->baseNovedad();
    }
    private function N17()
    {
        
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['sexo'])->first();
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $datosRelacionadosDelAfiliado->sexo->abreviatura;
    }
    private function N19()
    {
        
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['zona'])->first();
        $zonaAfiliado='';
        if($datosRelacionadosDelAfiliado->zona){
            $zonaAfiliado=$datosRelacionadosDelAfiliado->zona->codigo;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $zonaAfiliado;
    }
    private function N20()
    {
        
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $this->afiliado->nivel_sisben;
    }
    private function N21()
    {
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['poblacion_especial'])->first();
        $poblacionEspecial='';
        if($datosRelacionadosDelAfiliado->zona){
            $poblacionEspecial=$datosRelacionadosDelAfiliado->poblacion_especial->codigo;
        }
        
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $poblacionEspecial;
    }
    private function N24()
    {
        
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = '';//No se sabe de donde sacar el contrato
    }
    private function N25()
    {
        
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = '';//No se sabe de donde sacar la IPS primaria
    }
    private function N26()
    {
        
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = '';//No se sabe de donde sacar el contrato o poliza
    }
    private function N27()
    {
        
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = '';//No se sabe de donde sacar el contrato o poliza
        $this->nuevaNovedad['v2'] = '';//No se sabe de donde sacar el contrato o poliza
        
    }
    private function N28()
    {
        
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = '';//No se sabe de donde sacar el contrato o poliza
        $this->nuevaNovedad['v2'] = '';//No se sabe de donde sacar el contrato o poliza
        $this->nuevaNovedad['v3'] = '';//No se sabe de donde sacar el contrato o poliza
    }
    private function N29()
    {
        
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = '';//No se sabe de donde sacar el contrato o poliza
        $this->nuevaNovedad['v2'] = '';//No se sabe de donde sacar el contrato o poliza
    }
    private function N30()
    {
        
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = '';//No se sabe de donde sacar el contrato o poliza
    }
    private function N31()
    {
        
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['poblacion_especial'])->first();
        $poblacionEspecialSubsidiado='';
        if($datosRelacionadosDelAfiliado->poblacion_especial){
            $poblacionEspecialSubsidiado=$datosRelacionadosDelAfiliado->codigo;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $poblacionEspecialSubsidiado;
        $this->nuevaNovedad['v2'] = $this->afiliado->nivel_sisben;
    }
    private function N32()
    {
        
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['cabeza.tipo_id','parentesco','condicion_discapacidad'])->first();
        $tipoDocumentoCabezaFamilia='';
        $identificacionCabezaFamilia='';
        $parentescoConCabezaFamilia='';
        $condicionDiscapacidad='';
        if($datosRelacionadosDelAfiliado->cabeza){
            if($datosRelacionadosDelAfiliado->cabeza->tipo_id){
                $tipoDocumentoCabezaFamilia=$datosRelacionadosDelAfiliado->cabeza->tipo_id->abreviatura;
                $identificacionCabezaFamilia=$datosRelacionadosDelAfiliado->cabeza->identificacion;
            }
        }
        if($datosRelacionadosDelAfiliado->parentesco){
            $parentescoConCabezaFamilia=$datosRelacionadosDelAfiliado->parentesco->codigo;
        }
        if($datosRelacionadosDelAfiliado->condicion_discapacidad){
            $condicionDiscapacidad=$datosRelacionadosDelAfiliado->parentesco->abreviatura;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $tipoDocumentoCabezaFamilia;
        $this->nuevaNovedad['v2'] = $identificacionCabezaFamilia;
        $this->nuevaNovedad['v3'] = $parentescoConCabezaFamilia;
        $this->nuevaNovedad['v4'] = $condicionDiscapacidad;
    }
    private function N33()
    {
        
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['condicion_terminacion'])->first();
        $causaTerminacion='';
        if($datosRelacionadosDelAfiliado->condicion_terminacion){
            $causaTerminacion=$datosRelacionadosDelAfiliado->condicion_terminacion->codigo;
        }
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $causaTerminacion;
        $this->nuevaNovedad['v2'] = ToolsTrait::homologarFecha($this->afiliado->fecha_retiro);
    }
    private function N34()
    {
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = '';
        $this->nuevaNovedad['v2'] = '';
        $this->nuevaNovedad['v3'] = '';
        $this->nuevaNovedad['v4'] = '';
    }
    private function N35()
    {
        $datosRelacionadosDelAfiliado = $this->afiliado->with(['tipo_id'])->first();
        $this->nuevaNovedad = $this->baseNovedad();
        $this->nuevaNovedad['v1'] = $datosRelacionadosDelAfiliado->tipo_id->abreviatura;
        $this->nuevaNovedad['v2'] = $this->afiliado->identificacion;
        $this->nuevaNovedad['v3'] = ToolsTrait::homologarFecha($this->afiliado->fecha_afiliacion);
    }

    private function baseNovedad()
    {
        return [
            'as_tipnovedade_id' => $this->tipo->id,
            'as_afiliado_id' => $this->afiliado->id,
            'gn_municipio_id' => $this->afiliado->gn_municipio_id,
            'gn_tipdocidentidad_id' => $this->afiliado->gn_tipdocidentidad_id,
            'codigo_entidad' => $this->novedadOriginal->codigo_entidad,
            'identificacion' => $this->afiliado->identificacion,
            'apellido1' => $this->afiliado->apellido1,
            'apellido2' => $this->afiliado->apellido2,
            'nombre1' => $this->afiliado->nombre1,
            'nombre2' => $this->afiliado->nombre2,
            'fecha_nacimiento' => ToolsTrait::homologarFecha($this->afiliado->fecha_nacimiento),
            'fecha_ini_novedad'=> ToolsTrait::homologarFecha($this->novedadOriginal->fecha_ini_novedad)
        ];



    }
}