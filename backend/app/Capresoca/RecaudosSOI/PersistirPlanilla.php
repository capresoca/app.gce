<?php


namespace App\Capresoca\RecaudosSOI;


use App\Models\Compensacion\CpPila;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersistirPlanilla
{
    private $file;
    private $idCargaRecaudo;


    public function __construct($filePath,$idCargaRecaudo = null)
    {
        $this->file = $filePath;
        $this->idCargaRecaudo = $idCargaRecaudo;
    }

    public function handle()
    {
        try {
            DB::beginTransaction();
            $encabezado = (new EncabezadoPlanilla($this->file))->toArray();
            $cuerpo = (new CuerpoPlanilla($this->file))->toArray();

            $existe = CpPila::where([
                    ['nit','=',$encabezado['nit']],
                    ['identificacion_aportante','=',$encabezado['identificacion_aportante']],
                    ['periodo_pago','=',$encabezado['periodo_pago']],
                    ['numero_radicacion','=',$encabezado['numero_radicacion']]
                ])->exists();

            if(! $existe) {
                $pila = CpPila::create($encabezado);
                $pila->as_cargas_id = $this->idCargaRecaudo;
                $pila->save();
                $pila->pila_detalles()->createMany($cuerpo);
            }
            DB::commit();
            return true;
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'message' => 'Error en el servidor' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}