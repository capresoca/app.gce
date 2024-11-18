<?php


namespace App\Capresoca\Compensacion;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HistoricoACX
{



    public static function run($ruta)
    {
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
            LOAD DATA LOW_PRIORITY INFILE '{$ruta}'
            INTO TABLE {$nombre_tabla}
            FIELDS TERMINATED BY '¬'
            LINES TERMINATED BY '\r\n'
            IGNORE 1 LINES
            ({$campos})
            SET fecprocompe = concat(SUBSTRING(fecprocompe,7,4),'-',SUBSTRING(fecprocompe,4,2),'-',SUBSTRING(fecprocompe,1,2)),
            fecconsigapo = concat(SUBSTRING(fecconsigapo,7,4),'-',SUBSTRING(fecconsigapo,4,2),'-',SUBSTRING(fecconsigapo,1,2))
            ");



        DB::statement("INSERT INTO cp_acx SELECT * FROM {$nombre_tabla}");

        DB::statement("SET GLOBAL local_infile = `OFF`");
    }


}