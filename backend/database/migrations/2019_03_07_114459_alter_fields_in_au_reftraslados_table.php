<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInAuReftrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `au_reftraslados` 
                        CHANGE COLUMN `tipo_traslado` `tipo_traslado` ENUM('Terrestre', 'Aereo', 'Interno') NULL DEFAULT NULL ,
                        CHANGE COLUMN `tipo_ambulancia` `tipo_ambulancia` ENUM('Transporte Asistencial Basico', 'Transporte Asistencial Medicalizado', 'Otro') NULL DEFAULT NULL ;
                        ");
    }
}
