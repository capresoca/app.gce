<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddAlterDataEnumInCmFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Facturas
        DB::statement("ALTER TABLE `cm_radicados` 
                            CHANGE COLUMN `estado` `estado` ENUM('Radicado', 'En Auditoria', 'Finalizado', 'Anulado') NULL DEFAULT NULL");
    }
}
