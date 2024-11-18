<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsCmConcurrencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('cm_concurrencias', function (Blueprint $table) {
            $table->enum('via_ingreso',['Consulta Externa', 'Urgencias', 'Remitido']);
            $table->integer('rs_entorigen_id')->nullable();
            $table->integer('rs_cie10_ingreso');
            $table->integer('rs_cie10_relacionado')->nullable();

            $table->foreign('rs_entorigen_id')->references('id')->on('rs_entidades')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('rs_cie10_ingreso')->references('id')->on('rs_cie10s')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('rs_cie10_relacionado')->references('id')->on('rs_cie10s')->onUpdate('no action')->onDelete('restrict');
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
        Schema::table('cm_concurrencias', function (Blueprint $table) {
            //
        });
    }
}
