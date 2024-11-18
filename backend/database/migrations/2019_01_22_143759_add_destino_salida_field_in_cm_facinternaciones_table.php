<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDestinoSalidaFieldInCmFacinternacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_facinternaciones` 
                              CHANGE COLUMN `destino_salida` `destino_salida` ENUM('Alta de urgencias', 'Remisión a otro nivel de complejidad', 'Hospitalización') CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}



