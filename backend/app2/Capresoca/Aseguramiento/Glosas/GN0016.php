<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/09/2018
 * Time: 11:21 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;

use App\Models\Aseguramiento\AsPresunrepetido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GN0016 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
        try{
            Log::info('Glosa a GN016');
            DB::beginTransaction();
            $repetido = AsPresunrepetido::find($this->afiliado->id);

            if($repetido)
                return false;

            AsPresunrepetido::create(
                [
                    'as_afiliado_id' => $this->afiliado->id
                ]
            );

            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            throw $e;
        }


    }
}