<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsRsServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("INSERT INTO rs_servicios (id, rs_subgrupservicio_id, codigo, nombre, primario )  
                        VALUES (198,1,'918', 'PROMOCIÓN Y PREVENCIÓN GENERAL', 1)");
        DB::statement("INSERT INTO rs_servicios (id, rs_subgrupservicio_id, codigo, nombre, primario )  
                        VALUES (199,1,'919', 'PROMOCIÓN Y PREVENCIÓN - LABORATORIO', 1)");
        DB::statement("INSERT INTO rs_servicios (id, rs_subgrupservicio_id, codigo, nombre, primario )  
                        VALUES (200,9, '960', 'SERVICIO FARMACEUTICO - MEDIANA Y ALTA COMPLEJIDA', 1)");

        DB::statement("UPDATE rs_servicios SET primario = 1 WHERE id in (80,86,151,158,176,166,165,1,144,89,87,194,177,154)");
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
