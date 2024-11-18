<?php

namespace App\Jobs;

use App\Capresoca\AfiliadoTrait;
use App\Capresoca\LeerCsv;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\Compensacion\CpResultadosCompensacion;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcesarResultadoCompensacion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use LeerCsv, AfiliadoTrait;
    protected $compensacion;
    protected $parentescos;
    protected $errores_abx = [];
    protected $tipafiliados;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CpResultadosCompensacion $compensacion)
    {
        $this->compensacion = $compensacion;
        $this->parentescos = AsParentesco::all();
        $this->tipafiliados = AsTipafiliado::all();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $this->cargarACX();
            $this->procesarABX();
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
        }
    }

    private function procesarABX()
    {
        $readerABX = $this->leerFromUrl($this->compensacion->abx->ruta);
        $data = $readerABX->getRecords();

        foreach ($data as $datum) {
            try{

                $this->crearRelacionFamiliar($datum);

            }catch (\Exception $exception){
                Log::error($exception->getTraceAsString());
                array_push($this->errores_abx,$exception->getMessage());
                continue;
            }
        }

        $this->compensacion->estado = 'Procesado';
        $this->compensacion->errors_abx = json_encode($this->errores_abx);
        $this->compensacion->save();
    }

    /**
     * @param $datum
     * @throws \Exception
     */
    private function crearRelacionFamiliar($datum)
    {
        try{

            $cotizante = $this->getAfiliado($datum[3], $datum[4]);

            $beneficiario = $this->getAfiliado($datum[5], $datum[6]);

            $beneficiario->cabfamilianterior_id = $beneficiario->cabfamilia_id;
            $beneficiario->cabfamilia_id = $cotizante->id;
            $beneficiario->as_tipafiliado_id = $this->tipafiliados->where('codigo', $datum[7])->first()->id;
            $beneficiario->as_parentesco_id = $this->parentescos->where('codigo', $datum[8])->first()->id;
            $beneficiario->save();

        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public function getErrors()
    {
        return [
            'errores_abx' => $this->errores_abx
        ];
    }

    private function cargarACX()
    {
        $file = storage_path('app/' . $this->compensacion->abx->ruta);
        $file = str_replace('\\', '/', $file);
        $nombre_tabla = 'ACX' . Str::random(16);

        DB::statement("SET GLOBAL local_infile = `ON`");
        DB::statement("CREATE TABLE {$nombre_tabla} SELECT * FROM cp_acx LIMIT 0");
        DB::statement("ALTER TABLE {$nombre_tabla}
	        CHANGE COLUMN fecprocompe fecprocompe VARCHAR(10)");
        DB::statement("ALTER TABLE {$nombre_tabla}
	        CHANGE COLUMN fecconsigapo fecconsigapo VARCHAR(10)");

        DB::statement("UPDATE cp_acx set fecprocompe = concat(SUBSTRING(fecprocompe,7,4),'-',SUBSTRING(fecprocompe,4,2),'-',SUBSTRING(fecprocompe,1,2)),
                                fecconsigapo = concat(SUBSTRING(fecconsigapo,7,4),'-',SUBSTRING(fecconsigapo,4,2),'-',SUBSTRING(fecconsigapo,1,2))");

        $campos = 'codeps,fecprocompe,periodocompe,tipodoccot,numdoccot,tipocot,tipodocapo,numdocapo,ibc,totaldiascotiz,
                    valorcoti,fecconsigapo,codoperador,numplanilla,regcompen,codglosa,totaldiascompe,grupoentidad,
                    zonageografica,upcreconocida,provincapacidades,valorfondpyp,valorupcpyp,valorcotizsubsoli,seriabdua,seriaha';

        DB::statement("SET GLOBAL local_infile = `ON`");
        DB::connection()->getPdo()
            ->exec("
            LOAD DATA LOW_PRIORITY INFILE '{$file}'
            INTO TABLE {$nombre_tabla}
            FIELDS TERMINATED BY ','
            LINES TERMINATED BY '\r\n'
            IGNORE 1 LINES
            ({$campos})
            SET fecprocompe = concat(SUBSTRING(fecprocompe,7,4),'-',SUBSTRING(fecprocompe,4,2),'-',SUBSTRING(fecprocompe,1,2)),
            fecconsigapo = concat(SUBSTRING(fecconsigapo,7,4),'-',SUBSTRING(fecconsigapo,4,2),'-',SUBSTRING(fecconsigapo,1,2))
            ");



        //DB::statement("INSERT INTO cp_acx SELECT * FROM {$nombre_tabla}");

        DB::statement("SET GLOBAL local_infile = `OFF`");
    }
}
