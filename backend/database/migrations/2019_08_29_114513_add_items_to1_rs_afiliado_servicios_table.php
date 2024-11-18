<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsTo1RsAfiliadoServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('rs_afiliado_servicios', function (Blueprint $table) {
            $table->integer('rs_asignamasivo_id')->unsigned()->nullable();
            $table->foreign('rs_asignamasivo_id')->references('id')->on('rs_asignamasivos')->onUpdate('no action')->onDelete('restrict');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_afiliado_servicios', function (Blueprint $table) {
            //
        });
    }
}
