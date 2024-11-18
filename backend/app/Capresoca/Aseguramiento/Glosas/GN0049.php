<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/10/2018
 * Time: 2:34 PM
 */

namespace App\Capresoca\Aseguramiento\Glosas;


use App\Models\Aseguramiento\AsBduaaccione;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GN0049 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
        try{
            Log::info('Glosa a GN049');
            DB::beginTransaction();

            $fecha_nacimiento_old = $this->afiliado->fecha_nacimiento;
            $this->afiliado->fecha_nacimiento = ToolsTrait::homologarFecha($this->columnas[0]);
            $this->afiliado->save();

            $this->crearRetramite();

            $this->corregida();

            AsBduaaccione::create(
                [
                    'as_bduaregrespuesta_id' => $this->regRespuesta->id,
                    'as_afiliado_id' => $this->afiliado->id,
                    'accion' => 'Se actualizó la fecha de nacimiento del afiliado',
                    'new_values' => json_encode($this->afiliado->fecha_nacimiento, JSON_UNESCAPED_UNICODE),
                    'old_values' => json_encode($fecha_nacimiento_old, JSON_UNESCAPED_UNICODE)
                ]
            );

            DB::commit();
            return true;
        }catch (\Exception $exception)
        {
            DB::rollBack();
            Log::error($exception);
            Log::error($exception->getTrace());
            throw $exception;
        }
    }
}