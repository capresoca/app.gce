<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddEstadoInCmPacientesHospitalariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `cm_pacientes_hospitalarios` 
                    CHANGE COLUMN `estado` `estado` ENUM('Sin Asignar', 'Asignado', 'Cerrado') NOT NULL DEFAULT 'Sin Asignar'");
    }
}
