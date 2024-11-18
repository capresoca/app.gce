<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpPrincipiosActivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_principios_activos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_medicamento_id')->unsigned();
            $table->tinyInteger('ConOrden')->nullable();
            $table->string('CodPriAct',5)->nullable();
            $table->float('ConcCant')->nullable();
            $table->string('UMedConc',4)->nullable();
            $table->float('CantCont')->nullable();
            $table->string('UMedCantCont',4)->nullable();
            $table->foreign('mp_medicamento_id')->references('id')->on('mp_medicamentos')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('mp_principios_activos');
    }
}

