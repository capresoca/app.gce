<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldRsCupsseccionIdInRsCupssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

//        Schema::table('rs_cupss', function (Blueprint $table) {
//        $table->integer('rs_cupsseccion_id')->unsigned()->nullable();
//        $table->foreign('rs_cupsseccion_id', 'fk_rs_cupss_rs_cupsseccionesdx1')
//            ->references('id')->on('rs_cupssecciones')
//            ->onDelete('restrict')
//            ->onUpdate('no action');
//        });
//
//        DB::statement('INSERT INTO rs_cupssecciones(id, codigo, nombre)
//                      VALUES
//                      (1,"00","PROCEDIMIENTOS QUIRÚRGICOS E INTERVENCIONISTAS"),
//                      (2,"01","PROCEDIMIENTOS NO QUIRÚRGICOS"),
//                      (3,"02","PROCEDIMIENTOS E INTERVENCIONES SOBRE LA COMUNIDAD, SU ENTORNO Y SALUD"),
//                      (4,"03","PROCEDIMIENTOS E INTERVENCIONES HACIA LA PROTECCIÓN  DE LA SALUD DE LOS TRABAJADORES"),
//                      (5,"04","SERVICIOS DE SALUD")');
//
//        DB::statement('UPDATE rs_cupss set rs_cupsseccion_id = 1 WHERE id < 6367');
//        DB::statement('UPDATE rs_cupss set rs_cupsseccion_id = 2 WHERE id between 6367 AND 9288');
//        DB::statement('UPDATE rs_cupss set rs_cupsseccion_id = 3 WHERE id between 9289 AND 9579');
//        DB::statement('UPDATE rs_cupss set rs_cupsseccion_id = 4 WHERE id between 9580 AND 9752');
//        DB::statement('UPDATE rs_cupss set rs_cupsseccion_id = 5 WHERE id > 9752');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
