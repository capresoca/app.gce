<?php


namespace App\Capresoca\RecaudosSOI;


use App\Models\Compensacion\CpIP;
use App\Models\Compensacion\CpPila;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersistirIP
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
            $encabezado = (new EncabezadoIP($this->file))->toArray();
            $cuerpo = (new CuerpoIP($this->file))->toArray();


            if(!$this->pilaExiste($encabezado)) {
                $this->crearPlanilla($encabezado, $cuerpo);
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

    /**
     * @param $encabezado
     * @return mixed
     */
    private function pilaExiste($encabezado)
    {
        $existe = CpIP::where([
            ['nit', '=', $encabezado['nit']],
            ['identificacion_aportante', '=', $encabezado['identificacion_aportante']],
            ['periodo_pago', '=', $encabezado['periodo_pago']],
            ['tipo_planilla_pensionado', '=', $encabezado['tipo_planilla_pensionado']]
        ])->exists();
        return $existe;
    }

    /**
     * @param $encabezado
     * @param array $cuerpo
     */
    private function crearPlanilla($encabezado, array $cuerpo): void
    {
        $pila = CpIP::create($encabezado);
        $pila->detalles()->createMany($cuerpo);
    }
}