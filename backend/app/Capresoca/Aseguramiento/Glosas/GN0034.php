<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/10/2018
 * Time: 11:15 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;

use App\Models\Aseguramiento\AsBduaaccione;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GN0034 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
        try{
            Log::info('Glosa a GN034');
            DB::beginTransaction();

            $apellido1 = $this->afiliado->apellido1;
            $this->afiliado->apellido1 = $this->columnas[0];
            $this->afiliado->nombre_completo = $this->afiliado->nombre1.(empty($this->afiliado->nombre2) ? '' : ' '.$this->afiliado->nombre2).' '.$this->afiliado->apellido1.(empty($this->afiliado->apellido2) ? '' : ' '.$this->afiliado->apellido2);
            $this->afiliado->save();

            $this->crearRetramite();

            $this->corregida();

            AsBduaaccione::create(
                [
                    'as_bduaregrespuesta_id' => $this->regRespuesta->id,
                    'as_afiliado_id' => $this->afiliado->id,
                    'accion' => 'Se actualizó el primer apellido del afiliado',
                    'new_values' => json_encode($this->afiliado->apellido1, JSON_UNESCAPED_UNICODE),
                    'old_values' => json_encode($apellido1, JSON_UNESCAPED_UNICODE)
                ]
            );

            DB::commit();
            return true;
        }catch (\Exception $e)
        {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
    }
}

