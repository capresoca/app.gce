<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/10/2018
 * Time: 12:12 PM
 */

namespace App\Capresoca\Aseguramiento\Glosas;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GN0042 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
        try{
            Log::info('Glosa a GN042');
            DB::beginTransaction();
            $this->crearRetramite();
            $this->corregida();
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