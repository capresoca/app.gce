<?php

namespace App\Capresoca\Aseguramiento\Glosas;


use App\Models\Aseguramiento\AsPresunrepetido;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GN0003 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
        try{
            Log::info('Glosa a GN003');
            DB::beginTransaction();
            $repetido = AsPresunrepetido::find($this->afiliado->id);
            if($repetido){
            }else{
                AsPresunrepetido::create(
                    [
                        'as_afiliado_id' => $this->afiliado->id
                    ]
                );
            }
            $this->crearRetramite();
            DB::commit();

            return true;

        }catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            return false;
        }


    }
}