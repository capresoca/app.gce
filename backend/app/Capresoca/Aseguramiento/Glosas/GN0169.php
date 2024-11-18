<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/09/2018
 * Time: 11:20 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;

use App\Capresoca\Aseguramiento\Retramite;
use App\Models\Aseguramiento\AsBduaaccione;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\ToolsTrait;

class GN0169 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{
    protected $datos = array();
    protected $datosAntiguos = array();


    public function procesar()
    {
        try{
            Log::info('Glosa a GN0169');
            DB::beginTransaction();
            //Si la glosa trae retroalimentación,
            Log::info('Antes de corregir Afiliado: ' . $this->afiliado->identificacion .' Primer Apellido: '.$this->afiliado->apellido1);
            if($this->hasRetroalimentacion())
            {
                // corregir con los datos suministrados,
                $this->corregirAfiliado();
            }
            Log::info('Despues de corregir Afiliado: ' . $this->afiliado->identificacion .' Primer Apellido: '.$this->afiliado->apellido1);
            // volver a generar la novedad glosada para su posterior envío.
            // Si no presenta retroalimentación, la causa de la glosa es una evolución de documento. Se debe a volver enviar la novedad tal cual como esta.
            if(!$this->regRespuesta->nuevo_tramite){
                $nuevoTramite = new Retramite($this->regRespuesta->tramite);
                $this->regRespuesta->nuevo_tramite_id= $nuevoTramite->getNuevoTramite()->id;
                $this->regRespuesta->save();
            }

            $this->glosa->estado = 'Corregida';
            $this->glosa->as_nuevotramite_id = $this->regRespuesta->nuevo_tramite_id;
            $this->glosa->save();
            DB::commit();
            return true;
        }catch (\Exception $e){
            Log::error($e);
            DB::rollBack();
            return false;
        }


    }

    /**
     * @return bool
     */
    private function hasRetroalimentacion()
    {
        foreach ($this->columnas as $columna) {
            if($columna)
                return true;
        }
        return false;
    }


    private function setDatos($index)
    {
        if($index >= count($this->columnas)) return;

        $valor = $this->columnas[$index];

        if( $valor != ''){
            $propiedad_slug = str_replace(' ','_', strtolower($valor));

            $this->datos[$propiedad_slug] = $this->columnas[$index + 1];

            $this->setDatos($index + 3);

        }else{

            $this->setDatos($index + 1);
        }
    }



    private function corregirAfiliado()
    {
        $this->setDatos(0);
        foreach ($this->datos as $key => $dato) {
            $setterPropiedad = 'set_'.$key;
            if(method_exists($this,$setterPropiedad)){
                $this->$setterPropiedad();
            }
        }

        $this->afiliado->save();
        $accion = AsBduaaccione::create(
            [
                'as_bduaregrespuesta_id' => $this->regRespuesta->id,
                'as_afiliado_id' => $this->afiliado->id,
                'accion' => 'Se actualizaron los datos del afiliado',
                'new_values' => json_encode($this->datos, JSON_UNESCAPED_UNICODE),
                'old_values' => json_encode($this->datosAntiguos, JSON_UNESCAPED_UNICODE)
            ]
        );

    }


    private function set_primer_nombre()
    {
        $this->datosAntiguos['primer_nombre'] = $this->afiliado->nombre1;
        $this->afiliado->nombre1 = $this->datos['primer_nombre'];
    }

    private function set_segundo_nombre()
    {
        $this->datosAntiguos['segundo_nombre'] = $this->afiliado->nombre2;
        $this->afiliado->nombre2 = $this->datos['segundo_nombre'];
    }

    private function set_primer_apellido()
    {
        $this->datosAntiguos['primer_apellido'] = $this->afiliado->apellido1;
        $this->afiliado->apellido1 = $this->datos['primer_apellido'];
    }

    private function set_segundo_apellido()
    {
        $this->datosAntiguos['segundo_apellido'] = $this->afiliado->apellido2;
        $this->afiliado->apellido2 = $this->datos['segundo_apellido'];
    }

    private function set_fecha_nacimiento()
    {
        $this->datosAntiguos['fecha_nacimiento'] = $this->afiliado->fecha_nacimiento;
        $this->afiliado->fecha_nacimiento = ToolsTrait::homologarFecha($this->datos['fecha_nacimiento']);
    }

    private function set_sexo()
    {
        $this->datosAntiguos['sexo'] = $this->afiliado->sexo;
        $this->afiliado->gn_sexo_id = $this->datos['sexo'] === 'M' ? 2 : 1;
    }



}