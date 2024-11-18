<?php


namespace App\Capresoca\Compensacion;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HistoricoABX
{



    public static function run($ruta)
    {
        $nombre_tabla = 'ABX' . Str::random(16);

        DB::statement("SET GLOBAL local_infile = `ON`");
        DB::statement("CREATE TABLE {$nombre_tabla} SELECT * FROM cp_abx LIMIT 0");
        DB::statement("ALTER TABLE {$nombre_tabla}
	        CHANGE COLUMN fecprocompe fecprocompe VARCHAR(10)");
        DB::statement("ALTER TABLE {$nombre_tabla}
	        CHANGE COLUMN fecconsigupcadicio fecconsigupcadicio VARCHAR(10)");

        DB::statement("UPDATE {$nombre_tabla} set fecprocompe = concat(SUBSTRING(fecprocompe,7,4),'-',SUBSTRING(fecprocompe,4,2),'-',SUBSTRING(fecprocompe,1,2)),
                                fecconsigupcadicio = concat(SUBSTRING(fecconsigupcadicio,7,4),'-',SUBSTRING(fecconsigupcadicio,4,2),'-',SUBSTRING(fecconsigupcadicio,1,2))");

        $campos = 'codeps,fecprocompe,periodocompe,tipodoccot,numdoccot,tipodocben,numdocben,tipoafil,parentesco,
                    totaldiascompen,valorupcadicio,fecconsigupcadicio,codoperador,numplanilla,regcompen,codglosa,grupedad,
                    zonageografica,upcreconocida,valorupcfondpyp,valorupcadiciofosyga,valorsoliupcadicio,seriabdua,seriah';

        DB::statement("SET GLOBAL local_infile = `ON`");
        DB::connection()->getPdo()
            ->exec("
            LOAD DATA LOW_PRIORITY INFILE '{$ruta}'
            INTO TABLE {$nombre_tabla}
            FIELDS TERMINATED BY '¬'
            LINES TERMINATED BY '\r\n'
            IGNORE 1 LINES
            ({$campos})
            SET 
            fecprocompe = concat(SUBSTRING(fecprocompe,7,4),'-',SUBSTRING(fecprocompe,4,2),'-',SUBSTRING(fecprocompe,1,2)),
            fecconsigupcadicio = concat(SUBSTRING(fecconsigupcadicio,7,4),'-',SUBSTRING(fecconsigupcadicio,4,2),'-',SUBSTRING(fecconsigupcadicio,1,2))
            ");



        DB::statement("INSERT INTO cp_abx SELECT * FROM {$nombre_tabla}");

        DB::statement("SET GLOBAL local_infile = `OFF`");
    }


}