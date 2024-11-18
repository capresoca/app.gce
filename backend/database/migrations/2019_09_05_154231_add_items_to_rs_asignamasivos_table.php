<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsToRsAsignamasivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('rs_asignamasivos', function (Blueprint $table) {
            $table->enum('tipo_proceso',['Asignar servicios','Trasladar servicios']);
            $table->integer('rs_servhabilitado_anterior')->nullable();

            $table->foreign('rs_servhabilitado_anterior')->references('id')->on('rs_servhabilitados')->onUpdate('no action')->onDelete('restrict');
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
        Schema::table('rs_asignamasivos', function (Blueprint $table) {
            //
        });
    }
}
