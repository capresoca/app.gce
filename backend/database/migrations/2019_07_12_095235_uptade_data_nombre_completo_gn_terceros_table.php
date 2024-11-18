<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UptadeDataNombreCompletoGnTercerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `gn_terceros` SET `nombre_completo` = concat_ws(' ', `gn_terceros`.nombre1, `gn_terceros`.nombre2, `gn_terceros`.apellido1, `gn_terceros`.apellido2) where id >= 1;");
        DB::statement("UPDATE `gn_terceros` SET `nombre_completo` = `gn_terceros`.`razon_social` where id >= 1 and (nombre_completo = '' or nombre_completo = null)");
    }
}
